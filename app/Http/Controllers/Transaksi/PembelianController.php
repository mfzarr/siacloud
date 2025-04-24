<?php

namespace App\Http\Controllers\Transaksi;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Masterdata\Coa;
use Illuminate\Support\Carbon;
use App\Models\Masterdata\Produk;
use App\Models\Laporan\JurnalUmum;
use App\Models\Laporan\StokProduk;
use Illuminate\Support\Facades\DB;
use App\Models\Laporan\RekapHutang;
use App\Models\Masterdata\Supplier;
use App\Models\Transaksi\Pembelian;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Masterdata\stok_produk;
use App\Models\Transaksi\Pembeliandetail;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;


class PembelianController extends Controller
{
    public function index(Request $request)
    {
        $id_perusahaan = Auth::user()->id_perusahaan;
        $filter = $request->input('filter');
        $search = $request->input('search', '');
        $supplier_filter = $request->input('supplier');
        $month = $request->input('month');

        $query = Pembelian::with(['pembelianDetails', 'supplierRelation','rekap'])
            ->where('id_perusahaan', $id_perusahaan);

        if ($search) {
            $query->where('no_transaksi_pembelian', 'like', "%{$search}%");
        }

        if ($filter === 'lunas') {
            $query->whereColumn('total_dibayar', '>=', 'total');
        } elseif ($filter === 'belum_lunas') {
            $query->whereColumn('total_dibayar', '<', 'total');
        }

        if ($supplier_filter) {
            $query->where('supplier', $supplier_filter);
        }

        if ($month) {
            $query->whereMonth('tanggal_pembelian', Carbon::parse($month)->month)
                ->whereYear('tanggal_pembelian', Carbon::parse($month)->year);
        }

        $pembelian = $query->get(); // Remove pagination

        // Fetch all suppliers for the dropdown
        $suppliers = Supplier::where('id_perusahaan', $id_perusahaan)->get();

        return view('transaksi.pembelian.index', compact('pembelian', 'filter', 'search', 'suppliers', 'supplier_filter', 'month'));
    }

    public function create()
    {
        $id_perusahaan = Auth::user()->id_perusahaan;
        $suppliers = Supplier::where('id_perusahaan', $id_perusahaan)->with('products')->get();

        // Fetch all products for the company
        $produk = Produk::where('id_perusahaan', $id_perusahaan)->get();

        return view('transaksi.pembelian.create', compact('suppliers', 'produk'));
    }

    public function getProductsBySupplier($supplierId)
    {
        $id_perusahaan = Auth::user()->id_perusahaan;
        $products = Produk::where('id_perusahaan', $id_perusahaan)
            ->where('id_supplier', $supplierId)
            ->get();

        return response()->json($products);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'supplier' => 'required|exists:supplier,id_supplier',
            'tipe_pembayaran' => 'required|in:tunai,kredit',
            'produk' => 'required|array',
            'produk.*.id_produk' => 'required|exists:produk,id_produk',
            'produk.*.qty' => 'required|integer|min:1',
            'produk.*.harga' => 'required|numeric|min:0',
            'produk.*.dibayar' => 'required|numeric|min:0'
        ]);

        // Store the Pembelian (Purchase)
        $pembelian = Pembelian::create([
            'tanggal_pembelian' => $request->tanggal,
            'supplier' => $request->supplier,
            'tipe_pembayaran' => $request->tipe_pembayaran,
            'id_perusahaan' => Auth::user()->id_perusahaan,
        ]);

        // foreach ($request->produk as $item) {
        //     $pembelianDetail = $pembelian->pembelianDetails()->create([
        //         'id_produk' => $item['id_produk'],
        //         'harga' => $item['harga'],
        //         'kuantitas' => $item['kuantitas'],
        //     ]);

        //     $this->updateStokProduk($pembelianDetail);
        // }

        $total = 0;
        $total_dibayar = 0;

        foreach ($request->produk as $item) {
            $subtotal = $item['qty'] * $item['harga'];
            $total += $subtotal;
            $total_dibayar += $item['dibayar'];

            $pembelian->pembelianDetails()->create([
                'id_produk' => $item['id_produk'],
                'qty' => $item['qty'],
                'harga' => $item['harga'],
                'dibayar' => $item['dibayar'],
            ]);

            // Update the stock of the selected products
            $produk = Produk::find($item['id_produk']);
            $produk->stok += $item['qty'];
            $produk->save();

            //Update Stok Masuk
            $stok = stok_produk::firstOrCreate(
                ['id_produk' => $item['id_produk']],
                ['stok_masuk' => 0, 'stok_keluar' => 0, 'stok_akhir' => 0]
            );
            $stok->stok_masuk += $item['qty'];
            $stok->save();
        }

        $status = ($total_dibayar >= $total) ? 'Lunas' : 'Belum Lunas';

        $pembelian->update([
            'total' => $total,
            'total_dibayar' => $total_dibayar,
            'status' => $status,
        ]);

        // If the payment type is "kredit", store the debt in RekapHutang
        if ($pembelian->tipe_pembayaran === 'kredit') {
            $sisa_hutang = $pembelian->total - $pembelian->total_dibayar;

            RekapHutang::create([
                'uuid' => Str::uuid(),
                'pembelian_id' => $pembelian->id_pembelian,
                'id_supplier' => $pembelian->supplier, // This will now work
                'total_hutang' => $pembelian->total,
                'total_dibayar' => $pembelian->total_dibayar,
                'sisa_hutang' => $sisa_hutang,
                'tenggat_pelunasan' => Carbon::parse($pembelian->tanggal_pembelian)->addMonth(),
            ]);
        }

        // Create Journal Entries for the Pembelian (Purchase)
        $this->createJournalEntries($pembelian);

        return redirect()->route('pembelian.index')->with('success', 'Pembelian created successfully.');
    }

    public function destroy($id_pembelian)
    {
        $id_perusahaan = Auth::user()->id_perusahaan;

        $pembelian = Pembelian::where('id_pembelian', $id_pembelian)
            ->where('id_perusahaan', $id_perusahaan)
            ->firstOrFail();

        $pembelian->delete();

        return redirect()->route('pembelian.index')->with('success', 'Pembelian deleted successfully.');
    }

    public function show($id_pembelian)
    {
        $id_perusahaan = Auth::user()->id_perusahaan;

        $pembelian = Pembelian::with(['pembelianDetails', 'supplierRelation', 'pelunasanPembelian'])
            ->where('id_pembelian', $id_pembelian)
            ->where('id_perusahaan', $id_perusahaan)
            ->firstOrFail();

        $status = $pembelian->total_dibayar >= $pembelian->total
            ? '<span class="badge badge-success">Lunas</span>'
            : '<span class="badge badge-danger">Belum Lunas</span>';

        return view('transaksi.pembelian.detail', compact('pembelian', 'status'));
    }

    public function pelunasan(Request $request, $id_pembelian)
    {
        $pembelian = Pembelian::with('pembelianDetails')->findOrFail($id_pembelian);
    
        // Calculate the remaining payment
        $remainingPayment = $pembelian->total - $pembelian->total_dibayar;
    
        // Check if there's any remaining payment to process
        if ($remainingPayment <= 0) {
            return redirect()->route('pembelian.index')->with('error', 'Pembelian sudah lunas.');
        }
    
        // Create a pelunasan entry
        $pembelian->pelunasanPembelian()->create([
            'id_pelunasan' => Str::uuid(),
            'id_produk' => $pembelian->pembelianDetails->first()->id_produk,
            'total_pelunasan' => $remainingPayment,
            'tanggal_pelunasan' => $request->tanggal_pelunasan,
        ]);
    
        // Update total_dibayar and set status to 'Lunas'
        $pembelian->total_dibayar += $remainingPayment;
        $pembelian->status = 'Lunas';
        $pembelian->save();
    
        // Create the journal entries after the payment (pelunasan) is processed
        $this->createJournalEntries($pembelian);
    
        return redirect()->route('pembelian.index')->with('success', 'Pelunasan berhasil. Status Pembelian telah diubah menjadi Lunas.');
    }


    // Method to create journal entries for a Pembelian (Purchase)
    protected function createJournalEntries(Pembelian $pembelian)
    {
        $perusahaanId = $pembelian->id_perusahaan;
        $tanggal_pembelian = Carbon::parse($pembelian->tanggal_pembelian);
    
        // Function to get the correct COA based on date
        $getCoa = function ($kodeAkun, $tanggal) use ($perusahaanId) {
            $startOfMonth = $tanggal->copy()->startOfMonth();
            $endOfMonth = $tanggal->copy()->endOfMonth();
    
            return Coa::where('kode_akun', $kodeAkun)
                ->where('id_perusahaan', $perusahaanId)
                ->whereBetween('tanggal_saldo_awal', [$startOfMonth, $endOfMonth])
                ->first();
        };
    
        // Get the COA (Chart of Accounts) for relevant accounts
        $akunPembelian = $getCoa('1103', $tanggal_pembelian);
        $akunUtang = $getCoa('2101', $tanggal_pembelian);
        $akunKas = $getCoa('1101', $tanggal_pembelian);
    
        $transactionId = Str::uuid();
    
        // Prepare transaction entries
        $transactionData = [
            'transaction_id' => $transactionId,
            'entries' => []
        ];
    
        // Check if payment type is 'tunai' (cash)
        if ($pembelian->tipe_pembayaran === 'tunai') {
            $transactionData['entries'][] = [
                'id_coa' => $akunPembelian->id_coa,
                'tanggal_jurnal' => $tanggal_pembelian,
                'nama_akun' => $akunPembelian->nama_akun,
                'kode_akun' => $akunPembelian->kode_akun,
                'debit' => $pembelian->total,
                'credit' => null,
                'transaction_id' => $transactionId,
            ];
            $transactionData['entries'][] = [
                'id_coa' => $akunKas->id_coa,
                'tanggal_jurnal' => $tanggal_pembelian,
                'nama_akun' => $akunKas->nama_akun,
                'kode_akun' => $akunKas->kode_akun,
                'debit' => null,
                'credit' => $pembelian->total,
                'transaction_id' => $transactionId,
            ];
        }
        // Check if payment type is 'kredit' (credit)
        elseif ($pembelian->tipe_pembayaran === 'kredit') {
            $firstPaymentAmount = $pembelian->pembelianDetails->sum('dibayar');
            $sisaPayment = $pembelian->total - $firstPaymentAmount;
    
            $transactionData['entries'][] = [
                'id_coa' => $akunPembelian->id_coa,
                'tanggal_jurnal' => $tanggal_pembelian,
                'nama_akun' => $akunPembelian->nama_akun,
                'kode_akun' => $akunPembelian->kode_akun,
                'debit' => $pembelian->total,
                'credit' => null,
                'transaction_id' => $transactionId,
            ];
            $transactionData['entries'][] = [
                'id_coa' => $akunKas->id_coa,
                'tanggal_jurnal' => $tanggal_pembelian,
                'nama_akun' => $akunKas->nama_akun,
                'kode_akun' => $akunKas->kode_akun,
                'debit' => null,
                'credit' => $firstPaymentAmount,
                'transaction_id' => $transactionId,
            ];
            $transactionData['entries'][] = [
                'id_coa' => $akunUtang->id_coa,
                'tanggal_jurnal' => $tanggal_pembelian,
                'nama_akun' => $akunUtang->nama_akun,
                'kode_akun' => $akunUtang->kode_akun,
                'debit' => null,
                'credit' => $sisaPayment,
                'transaction_id' => $transactionId,
            ];
        }
    
        // Create journal entries for this transaction
        JurnalUmum::createFromTransaction($transactionData, $perusahaanId);
    
        // Add the entry for pelunasan (if any payment is made)
        if ($pembelian->pelunasanPembelian()->exists()) {
            $pelunasanAmount = $pembelian->pelunasanPembelian->sum('total_pelunasan');
            $tanggal_pelunasan = Carbon::parse($pembelian->pelunasanPembelian->first()->tanggal_pelunasan);
            $pelunasanTransactionId = Str::uuid();
    
            // Get updated COA for pelunasan date
            $akunUtangPelunasan = $getCoa('2101', $tanggal_pelunasan);
            $akunKasPelunasan = $getCoa('1101', $tanggal_pelunasan);
    
            // Prepare transaction entries for pelunasan
            $pelunasanTransactionData = [
                'transaction_id' => $pelunasanTransactionId,
                'entries' => [
                    [
                        'id_coa' => $akunUtangPelunasan->id_coa,
                        'tanggal_jurnal' => $tanggal_pelunasan,
                        'nama_akun' => $akunUtangPelunasan->nama_akun,
                        'kode_akun' => $akunUtangPelunasan->kode_akun,
                        'debit' => $pelunasanAmount,
                        'credit' => null,
                        'transaction_id' => $pelunasanTransactionId,
                    ],
                    [
                        'id_coa' => $akunKasPelunasan->id_coa,
                        'tanggal_jurnal' => $tanggal_pelunasan,
                        'nama_akun' => $akunKasPelunasan->nama_akun,
                        'kode_akun' => $akunKasPelunasan->kode_akun,
                        'debit' => null,
                        'credit' => $pelunasanAmount,
                        'transaction_id' => $pelunasanTransactionId,
                    ]
                ]
            ];
    
            // Create journal entries for pelunasan transaction
            JurnalUmum::createFromTransaction($pelunasanTransactionData, $perusahaanId);
        }
    }

    public function rekapHutang(Request $request)
    {
        $id_perusahaan = Auth::user()->id_perusahaan;
        $month = $request->input('month', Carbon::now()->format('Y-m'));
    
        // Get pembelian data with tipe_pembayaran 'kredit' and filter based on tenggat_pelunasan
        $pembelian = Pembelian::with(['pembelianDetails', 'supplierRelation'])
            ->where('id_perusahaan', $id_perusahaan)
            ->whereHas('rekap', function ($query) use ($month) {
                $query->whereMonth('tanggal_pembelian', Carbon::parse($month)->month)
                      ->whereYear('tanggal_pembelian', Carbon::parse($month)->year);
            })
            ->get();
    
        foreach ($pembelian as $item) {
            $sisa_hutang = $item->total - $item->total_dibayar;
    
            // Check if record exists in RekapHutang
            $rekap = RekapHutang::updateOrCreate(
                ['pembelian_id' => $item->id_pembelian],
                [
                    'id_supplier' => $item->supplier,
                    'total_hutang' => $item->total,
                    'total_dibayar' => $item->total_dibayar,
                    'sisa_hutang' => $sisa_hutang,
                    'tenggat_pelunasan' => Carbon::parse($item->tanggal_pembelian)->addMonth()
                ]
            );
    
            $item->setRelation('rekap', $rekap);
        }
    
        return view('transaksi.pembelian.rekap_hutang', compact('pembelian'));
    }
    

    public function updateDueDate(Request $request, $pembelian_id)
    {
        $rekap = RekapHutang::where('pembelian_id', $pembelian_id)->first();

        if (!$rekap) {
            return redirect()->back()->with('error', 'Data not found.');
        }

        $rekap->update([
            'tenggat_pelunasan' => $request->tenggat_pelunasan
        ]);

        return redirect()->back()->with('success', 'Due date updated successfully.');
    }

    public function updateBulk(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:rekap_hutang,id',
            'tenggat_pelunasan' => 'required|date',
        ]);
    
        try {
            DB::beginTransaction();
    
            $rekapHutang = RekapHutang::findOrFail($request->input('id'));
            $rekapHutang->tenggat_pelunasan = $request->input('tenggat_pelunasan');
            $rekapHutang->save();
    
            DB::commit();
    
            return redirect()->route('rekap_hutang')->with('success', 'Tenggat pelunasan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('rekap_hutang')->with('error', 'Gagal memperbarui tenggat pelunasan.');
        }
    }
    

public function updateTenggat(Request $request)
{
    $request->validate([
        'id_pembelian' => 'required|exists:pembelian,id_pembelian',
        'tenggat_pelunasan' => 'required|date',
    ]);

    $pembelian = Pembelian::findOrFail($request->id_pembelian);
    
    // Update the RekapHutang record
    $rekapHutang = RekapHutang::where('pembelian_id', $request->id_pembelian)->first();
    
    if ($rekapHutang) {
        $rekapHutang->tenggat_pelunasan = $request->tenggat_pelunasan;
        $rekapHutang->save();
        
        // Reload the updated data
        $pembelian->load('rekap');  // Ensure the latest data is loaded
        
        return redirect()->route('rekap_hutang')->with('success', 'Tenggat pelunasan berhasil diperbarui.');
    } else {
        return response()->json(['message' => 'Rekap hutang tidak ditemukan.'], 404);
    }
}
public function getPembelianDetail($id_pembelian)
{
    $id_perusahaan = Auth::user()->id_perusahaan;

    // Ambil data pembelian beserta relasi yang diperlukan
    $pembelian = Pembelian::with(['pembelianDetails', 'supplierRelation', 'rekap', 'rekap.pelunasanPembelian', 'pembelianDetails.produkrelation'])
        ->where('id_pembelian', $id_pembelian)
        ->where('id_perusahaan', $id_perusahaan)
        ->firstOrFail();

    // Mengembalikan view dengan data detail pembelian
    return view('transaksi.pembelian.detail_rekap', compact('pembelian'));
}

public function exportPdf()
{
    $id_perusahaan = Auth::user()->id_perusahaan;
    $pembelians = Pembelian::with(['pembelianDetails', 'supplierRelation'])
        ->where('id_perusahaan', $id_perusahaan)
        ->get()->map(function ($pembelian) {
            // Total Spend
            $totalSpend = $pembelian->total;

            // Products Purchased
            $productsPurchased = $pembelian->pembelianDetails->pluck('produkrelation.nama_produk')->toArray();

            // Last Purchase Date
            $lastPurchaseDate = $pembelian->tanggal_pembelian;

            // Payment Methods
            $paymentMethods = $pembelian->tipe_pembayaran;

            return [
                'no_transaksi' => $pembelian->no_transaksi_pembelian,
                'supplier' => $pembelian->supplierRelation->nama_supplier,
                'totalSpend' => $totalSpend,
                'productsPurchased' => implode(', ', $productsPurchased),
                'lastPurchaseDate' => $lastPurchaseDate,
                'paymentMethods' => $paymentMethods,
            ];
        });

    $pdf = PDF::loadView('transaksi.pembelian.laporan_pdf', compact('pembelians'))->setPaper('a4', 'landscape');
    return $pdf->download('laporan_pembelian.pdf');
}

public function exportExcel()
{
    $id_perusahaan = Auth::user()->id_perusahaan;
    $pembelian = Pembelian::with(['pembelianDetails', 'supplierRelation', 'rekap'])
        ->where('id_perusahaan', $id_perusahaan)
        ->get();

    return Excel::download(new class($pembelian) implements FromCollection, WithHeadings {
        protected $pembelian;

        public function __construct($pembelian)
        {
            $this->pembelian = $pembelian;
        }

        public function collection()
        {
            return $this->pembelian->map(function ($item) {
            return [
                'Tanggal' => $item->tanggal_pembelian,
                'Supplier' => $item->supplierRelation->nama_supplier,
                'Total' => $item->total,
                'Status' => $item->status,
                'Sisa Hutang' => $item->rekap ? $item->rekap->sisa_hutang : 0,
                'No Transaksi' => $item->no_transaksi_pembelian,
                'Produk' => $item->pembelianDetails->pluck('produkrelation.nama_produk')->implode(', '),
                'Qty' => $item->pembelianDetails->pluck('qty')->implode(', '),
            ];
            });
        }

        public function headings(): array
        {
            return [
            'No Transaksi',
            'Tanggal',
            'Supplier',
            'Total',
            'Status',
            'Sisa Hutang',
            'Produk',
            'Qty',
            ];
        }
    }, 'rekap_pembelian.xlsx');
}

}
