<?php

namespace App\Http\Controllers\Masterdata;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Masterdata\Produk;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use App\Models\Masterdata\Kategori_barang;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $id_perusahaan = Auth::user()->id_perusahaan;
        $produk = Produk::where('id_perusahaan', $id_perusahaan)->get();

        // Produk dengan stok di bawah 25%
        $lowStockProduk = $produk->filter(function ($item) {
            return $item->stok < 25;
        });

        return view('masterdata.produk.index', compact('produk', 'lowStockProduk'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $id_perusahaan = Auth::user()->id_perusahaan;
        $kategori_barang = Kategori_barang::where('id_perusahaan', $id_perusahaan)->get();
        return view('masterdata.produk.create', compact('kategori_barang'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id_produk)
    {
        $id_perusahaan = Auth::user()->id_perusahaan;
        $produk = Produk::where('id_perusahaan', $id_perusahaan)->findOrFail($id_produk);
        return view('masterdata.produk.show', compact('produk'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:255',
            'id_kategori_barang' => 'required|exists:kategori_barang,id_kategori_barang',
            'stok' => 'required|string',
            'harga' => 'required|string',
            'hpp' => 'required|string',
            'status' => 'required',
        ]);

        $stok = str_replace('.', '', $request->stok);
        $harga = str_replace('.', '', $request->harga);
        $hpp = str_replace('.', '', $request->hpp);
    
        $id_perusahaan = Auth::user()->id_perusahaan;
    
        $produk = Produk::create([
            'nama' => $request->nama,
            'id_kategori_barang' => $request->id_kategori_barang,
            'stok' => $stok,
            'stok_awal' => $stok, // Add this line
            'harga' => $harga,
            'hpp' => $hpp,
            'status' => $request->status,
            'id_perusahaan' => $id_perusahaan,
        ]);
    
        $id_produk = $produk->id_produk;
        $stok_awal = $request->stok;
        $bulan = date('Y-m-01');
    
        $stok_masuk = DB::table('pembelian_detail')
            ->where('id_produk', $id_produk)
            ->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->sum('qty');
    
        $stok_keluar = DB::table('penjualan_detail')
            ->where('id_produk', $id_produk)
            ->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->sum('kuantitas');
    
        $stok_akhir = $stok_awal - $stok_keluar;
    
        // Generate a UUID for id_stok_produk
        $id_stok_produk = Str::uuid();
    
        DB::table('stok_produk')->insert([
            'id_stok_produk' => $id_stok_produk, // Add this line
            'id_produk' => $id_produk,
            'stok_awal' => $stok_awal,
            'stok_masuk' => $stok_masuk,
            'stok_keluar' => $stok_keluar,
            'stok_akhir' => $stok_akhir,
            'bulan' => $bulan,
            'id_perusahaan' => $id_perusahaan,
        ]);
    
        return redirect()->route('produk.index')->with('success', 'Produk created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $id_perusahaan = Auth::user()->id_perusahaan;
        $produk = Produk::where('id_perusahaan', $id_perusahaan)->findOrFail($id);
        $kategori_barang = Kategori_barang::where('id_perusahaan', $id_perusahaan)->get();
        return view('masterdata.produk.edit', compact('produk', 'kategori_barang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produk $produk)
    {
        $request->validate([
            'nama' => 'required|max:255',
            'id_kategori_barang' => 'required|exists:kategori_barang,id_kategori_barang',
            'stok' => 'required|string',
            'harga' => 'required|string',
            'hpp' => 'required|string',
            'status' => 'required',
        ]);

        //konversi input
        $stok = str_replace('.', '', $request->stok);
        $harga = str_replace('.', '', $request->harga);
        $hpp = str_replace('.', '', $request->hpp);

        $produk->update([
            'nama' => $request->nama,
            'id_kategori_barang' => $request->id_kategori_barang,
            'stok' => $stok,
            'harga' => $harga,
            'hpp' => $hpp,
            'status' => $request->status,
        ]);

        return redirect()->route('produk.index')->with('success', 'Produk updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_produk)
    {
        $id_perusahaan = Auth::user()->id_perusahaan;

        DB::beginTransaction();

        try {
            // Hapus stok_produk terkait
            DB::table('stok_produk')
                ->where('id_produk', $id_produk)
                ->where('id_perusahaan', $id_perusahaan)
                ->delete();

            // Hapus produk
            $produk = Produk::where('id_produk', $id_produk)
                ->where('id_perusahaan', $id_perusahaan)
                ->firstOrFail();
            $produk->delete();

            DB::commit();

            return redirect()->route('produk.index')->with('success', 'Produk dan stok terkait berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('produk.index')->with('error', 'Gagal menghapus produk. Silakan coba lagi.');
        }
    }

    public function getProductLog($id_produk)
    {
        $id_perusahaan = Auth::user()->id_perusahaan;
        $produk = Produk::where('id_perusahaan', $id_perusahaan)->findOrFail($id_produk);
    
        // Ambil data pembelian
        $pembelian = DB::table('pembelian_detail')
            ->join('pembelian', 'pembelian_detail.id_pembelian', '=', 'pembelian.id_pembelian')
            ->where('pembelian_detail.id_produk', $id_produk)
            ->where('pembelian.id_perusahaan', $id_perusahaan)
            ->select('pembelian.tanggal_pembelian as tanggal', 'pembelian_detail.qty as jumlah', DB::raw('"Pembelian" as tipe'))
            ->get();
    
        // Ambil data penjualan
        $penjualan = DB::table('penjualan_detail')
            ->join('penjualan', 'penjualan_detail.id_penjualan', '=', 'penjualan.id_penjualan')
            ->where('penjualan_detail.id_produk', $id_produk)
            ->where('penjualan.id_perusahaan', $id_perusahaan)
            ->select('penjualan.tgl_transaksi as tanggal', 'penjualan_detail.kuantitas as jumlah', DB::raw('"Penjualan" as tipe'))
            ->get();
    
        // Gabungkan data pembelian dan penjualan
        $log = $pembelian->concat($penjualan)->sortBy('tanggal');
    
        // Hitung perubahan stok
        $stok_akhir = $produk->stok_awal;
        $perubahan_stok = [];
        foreach ($log as $item) {
            if ($item->tipe == 'Penjualan') {
                $stok_akhir -= $item->jumlah;
                $stok_sebelum = $stok_akhir + $item->jumlah;
            } else { // Pembelian
                $stok_akhir += $item->jumlah;
                $stok_sebelum = $stok_akhir - $item->jumlah;
            }
            $perubahan_stok[] = [
                'tanggal' => $item->tanggal,
                'tipe' => $item->tipe,
                'jumlah' => $item->jumlah,
                'stok_sebelum' => $stok_sebelum,
                'stok_akhir' => $stok_akhir
            ];
        }
    
        return view('masterdata.produk.log', compact('produk', 'perubahan_stok'));
    }

    public function kartustok()
    {
        $id_perusahaan = Auth::user()->id_perusahaan;
        $produk = Produk::where('id_perusahaan', $id_perusahaan)->get();

        return view('masterdata.produk.kartu_stok', compact('produk'));
    }

}
