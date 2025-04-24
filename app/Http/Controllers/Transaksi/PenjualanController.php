<?php

namespace App\Http\Controllers\Transaksi;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Masterdata\Coa;
use App\Models\Masterdata\Produk;
use App\Models\Laporan\JurnalUmum;
use Illuminate\Support\Facades\DB;
use App\Models\Masterdata\Discount;
use App\Models\Masterdata\Karyawan;
use App\Models\Transaksi\Penjualan;
use App\Http\Controllers\Controller;
use App\Models\Masterdata\Pelanggan;
use Illuminate\Support\Facades\Auth;
use App\Models\Masterdata\stok_produk;
use App\Models\Transaksi\PenjualanDetail;
use App\Models\Laporan\StokProduk;


class PenjualanController extends Controller
{
    /**
     * Display a listing of all Penjualan filtered by the authenticated user's company.
     */
    public function index(Request $request)
    {
        $id_perusahaan = Auth::user()->id_perusahaan;

        $filter = $request->input('filter');
        $search = $request->input('search', '');
        $month = $request->input('month');

        $query = Penjualan::with(['penjualanDetails', 'pelangganRelation', 'userRelation'])
            ->where('id_perusahaan', $id_perusahaan);

        if ($search) {
            $query->where('no_transaksi_penjualan', 'like', "%{$search}%");
        }

        if ($filter === 'lunas') {
            $query->where('status', 'Lunas');
        } elseif ($filter === 'belum_lunas') {
            $query->where('status', 'Belum Lunas');
        } elseif ($filter === 'selesai') {
            $query->where('status', 'Selesai');
        }

        if ($month) {
            $query->whereMonth('tgl_transaksi', Carbon::parse($month)->month)
                  ->whereYear('tgl_transaksi', Carbon::parse($month)->year);
        }

        $penjualan = $query->get();

        return view('transaksi.penjualan.index', compact('penjualan', 'filter', 'search', 'month'));
    }


    /**
     * Show the form for creating a new Penjualan.
     */
    public function create()
    {
        $id_perusahaan = Auth::user()->id_perusahaan;

        // Fetch data for the dropdowns
        $pelanggan = Pelanggan::where('id_perusahaan', $id_perusahaan)->get();
        $produk = Produk::where('id_perusahaan', $id_perusahaan)->get();
        $pegawai = Karyawan::where('id_perusahaan', $id_perusahaan)->get();

        // Pass discounts for all possible transactions
        $discounts = Discount::where('id_perusahaan', $id_perusahaan)->get()->keyBy('min_transaksi'); // Organize by transaction number

        return view('transaksi.penjualan.create', compact('pelanggan', 'produk', 'pegawai', 'discounts'));
    }

    /**
     * Store a newly created Penjualan in the database.
     */

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'pelanggan' => 'required|exists:pelanggan,id_pelanggan',
            'produk' => 'required|array',
            'produk.*.id_produk' => 'required|exists:produk,id_produk',
            'produk.*.kuantitas' => 'required|integer|min:1',
            'produk.*.pegawai' => 'required|exists:karyawan,id_karyawan',
        ]);
        foreach ($request->produk as $item) {
            $produk = Produk::find($item['id_produk']);
            if ($produk->stok < $item['kuantitas']) {
                return redirect()->back()->withErrors(["Stok tidak mencukupi untuk produk {$produk->nama_produk}. Stok tersedia: {$produk->stok}"]);
            }
        }
        $total = 0;
        $total_hpp = 0;
        foreach ($request->produk as $item) {
            $total += $item['kuantitas'] * $item['harga'];
            $produk = Produk::find($item['id_produk']);
            $total_hpp += $item['kuantitas'] * $produk->hpp; 
        }

        $id_perusahaan = Auth::user()->id_perusahaan;

        $pelanggan = Pelanggan::findOrFail($request->pelanggan);

        // Calculate the current transaction number
        $current_transaction_number = $pelanggan->jumlah_transaksi + 1;

        // Fetch discount for this transaction from the database
        $discount = Discount::where('min_transaksi', $current_transaction_number)
                    ->where('id_perusahaan', $id_perusahaan)
                    ->first();

        // Determine discount percentage and calculate discount amount
        $discount_percentage = $discount->discount_percentage ?? 0;
        $discount_amount = ($total * $discount_percentage) / 100;

        // Create the Penjualan record
        $penjualan = Penjualan::create([
            'tgl_transaksi' => $request->tanggal,
            'id_pelanggan' => $request->pelanggan,
            'id' => Auth::user()->id,
            'id_perusahaan' => Auth::user()->id_perusahaan,
            'status' => 'Selesai',
            'no_transaksi_penjualan' => $this->generateNoTransaksi(),
            'total' => $total - $discount_amount,
            'discount' => $discount_percentage,
            'hpp' => $total_hpp,
        ]);
        
        // sementara
        // foreach ($request->produk as $item) {
        //     $penjualanDetail = $penjualan->penjualanDetails()->create([
        //         'id_produk' => $item['id_produk'],
        //         'harga' => $item['harga'],
        //         'kuantitas' => $item['kuantitas'],
        //         'id_pegawai' => $item['pegawai'],
        //     ]);

        //     $this->updateStokProduk($penjualanDetail);
        // }

        // Save the details
        foreach ($request->produk as $item) {
            $penjualan->penjualanDetails()->create([
                'id_produk' => $item['id_produk'],
                'harga' => $item['harga'],
                'kuantitas' => $item['kuantitas'],
                'id_pegawai' => $item['pegawai'],
                // 'hpp' => $total_hpp,
            ]);
        }

        $produk = Produk::find($item['id_produk']);
        $produk->stok -= $item['kuantitas'];
        $produk->save();

        $stok = stok_produk::firstOrCreate(
            ['id_produk' => $item['id_produk']],
            ['stok_masuk' => 0, 'stok_keluar' => 0, 'stok_akhir' => 0]
        );
        $stok->stok_keluar += $item['kuantitas'];
        $stok->save(); 

        // Update jumlah_transaksi for the pelanggan
        $pelanggan->increment('jumlah_transaksi');

        // Call createJournalForPenjualan to create the journal entry
        $this->createJournalForPenjualan($penjualan);


        return redirect()->route('penjualan.index')->with('success', 'Penjualan created successfully.');
    }


    /**
     * Generate the journal entries after a Penjualan is created.
     */
    protected function createJournalForPenjualan(Penjualan $penjualan)
    {
        $perusahaanId = $penjualan->id_perusahaan;
        $tanggal_penjualan = Carbon::parse($penjualan->tgl_transaksi);
    
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
        $akunKas = $getCoa('1101', $tanggal_penjualan);
        $akunPenjualan = $getCoa('4101', $tanggal_penjualan);
        $akunHpp = $getCoa('5101', $tanggal_penjualan);
        $akunPersediaan = $getCoa('1103', $tanggal_penjualan);
    
        // Generate a unique transaction ID for this group of journal entries
        $transactionId = Str::uuid();
    
        // Prepare the transaction entries
        $transactionData = [
            'transaction_id' => $transactionId,
            'entries' => [
                [
                    'id_coa' => $akunKas->id_coa,
                    'tanggal_jurnal' => $penjualan->tgl_transaksi,
                    'nama_akun' => $akunKas->nama_akun,
                    'kode_akun' => $akunKas->kode_akun,
                    'debit' => $penjualan->total,
                    'credit' => null,
                    'transaction_id' => $transactionId,
                ],
                [
                    'id_coa' => $akunPenjualan->id_coa,
                    'tanggal_jurnal' => $penjualan->tgl_transaksi,
                    'nama_akun' => $akunPenjualan->nama_akun,
                    'kode_akun' => $akunPenjualan->kode_akun,
                    'debit' => null,
                    'credit' => $penjualan->total,
                    'transaction_id' => $transactionId,
                ],
                [
                    'id_coa' => $akunHpp->id_coa,
                    'tanggal_jurnal' => $penjualan->tgl_transaksi,
                    'nama_akun' => $akunHpp->nama_akun,
                    'kode_akun' => $akunHpp->kode_akun,
                    'debit' => $penjualan->hpp,
                    'credit' => null,
                    'transaction_id' => $transactionId,
                ],
                [
                    'id_coa' => $akunPersediaan->id_coa,
                    'tanggal_jurnal' => $penjualan->tgl_transaksi,
                    'nama_akun' => $akunPersediaan->nama_akun,
                    'kode_akun' => $akunPersediaan->kode_akun,
                    'debit' => null,
                    'credit' => $penjualan->hpp,
                    'transaction_id' => $transactionId,
                ]
            ],
        ];
    
        // Create journal entries for this transaction
        JurnalUmum::createFromTransaction($transactionData, $perusahaanId);
    }

    private function generateNoTransaksi()
    {
        $date = Carbon::now()->format('Ymd');

        // Set default id to 1 if no record exists yet
        $lastTransaction = Penjualan::whereDate('created_at', Carbon::today())->orderBy('no_transaksi_penjualan', 'desc')->first();
        $nextId = $lastTransaction ? intval(substr($lastTransaction->no_transaksi_penjualan, -4)) + 1 : 1;
        $formattedId = str_pad($nextId, 4, '0', STR_PAD_LEFT);

        return "PJL/{$date}/{$formattedId}";
    }


    /**
     * Remove the specified Penjualan from the database.
     */
    public function destroy($id_penjualan)
    {
        $id_perusahaan = Auth::user()->id_perusahaan;

        // Find the Penjualan using the correct primary key column
        $penjualan = Penjualan::where('id_penjualan', $id_penjualan)
            ->where('id_perusahaan', $id_perusahaan)
            ->firstOrFail();

        // Delete the found record
        $penjualan->delete();

        return redirect()->route('penjualan.index')->with('success', 'Penjualan deleted successfully.');
    }

    /**
     * Display the details of a specific Penjualan.
     */
    public function show($id_penjualan)
    {
        $id_perusahaan = Auth::user()->id_perusahaan;

        // Fetch penjualan with its details, pelanggan, and pegawai relations
        $penjualan = Penjualan::with(['penjualanDetails.produkRelation', 'penjualanDetails.pegawaiRelation', 'pelangganRelation', 'pegawaiRelation'])
            ->where('id_penjualan', $id_penjualan)
            ->where('id_perusahaan', $id_perusahaan)
            ->firstOrFail();

        return view('transaksi.penjualan.detail', compact('penjualan'));
    }

    public function edit($id_penjualan)
    {
        $penjualan = Penjualan::with(['penjualanDetails.produkRelation', 'penjualanDetails.pegawaiRelation'])->findOrFail($id_penjualan);

        $produk = Produk::where('id_perusahaan', Auth::user()->id_perusahaan)->get();
        $pegawai = Karyawan::where('id_perusahaan', Auth::user()->id_perusahaan)->get(); // Fetch all Pegawai

        return view('transaksi.penjualan.edit', compact('penjualan', 'produk', 'pegawai'));
    }


    public function updateSelesai(Request $request, $id_penjualan)
    {
        $request->validate([
            'produk' => 'required|array|min:1',
            'produk.*.id_produk' => 'required|exists:produk,id_produk',
            'produk.*.kuantitas' => 'required|integer|min:1',
            'produk.*.pegawai' => 'required|exists:karyawan,id_karyawan',
        ]);

        // Fetch and update the Penjualan record
        $penjualan = Penjualan::findOrFail($id_penjualan);
        $penjualan->penjualanDetails()->delete(); // Remove existing details

        // Add updated produk
        foreach ($request->produk as $item) {
            $penjualan->penjualanDetails()->create([
                'id_produk' => $item['id_produk'],
                'harga' => $item['harga'],
                'kuantitas' => $item['kuantitas'],
                'id_pegawai' => $item['pegawai'],
            ]);
        }

        $penjualan->update(['status' => 'Selesai']);

        return redirect()->route('penjualan.index')->with('success', 'Penjualan berhasil dirubah dan di Selesaikan.');
    }
    // protected function updateStokProduk(PenjualanDetail $penjualanDetail)
    // {
    //     DB::transaction(function () use ($penjualanDetail) {
    //         $produk = Produk::findOrFail($penjualanDetail->id_produk);
    //         $stokSebelum = $produk->stok;
    //         $produk->stok -= $penjualanDetail->kuantitas;
    //         $produk->save();

    //         StokProduk::create([
    //             'id_produk' => $penjualanDetail->id_produk,
    //             'id_penjualan_detail' => $penjualanDetail->id_penjualan_detail,
    //             'jumlah' => $penjualanDetail->kuantitas,
    //             'jenis' => 'penjualan',
    //             'stok_sebelum' => $stokSebelum,
    //             'stok_sesudah' => $produk->stok,
    //         ]);
    //     });
    // }
}
