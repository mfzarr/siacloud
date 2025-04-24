<?php

namespace App\Http\Controllers\Transaksi;

use App\Models\Transaksi\Pembeliandetail;
use App\Models\Transaksi\Pembelian;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PembeliandetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id_pembelian)
    {
        $pembelian = Pembelian::with('pembelianDetails')->findOrFail($id_pembelian);
        $pembeliandetails = $pembelian->pembelianDetails;
        $produk = DB::table('produk')->where('id_perusahaan', $pembelian->id_perusahaan)->get();

        return view('transaksi.pembelian.detail.index', compact('pembeliandetails', 'produk', 'pembelian'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_produk' => 'required',
            'id_pembelian' => 'required',
            'kuantitas' => 'required|integer',
            'harga' => 'required|numeric',
        ]);

        Pembeliandetail::create([
            'id_pembelian' => $request->id_pembelian,
            'id_produk' => $request->id_produk,
            'kuantitas' => $request->kuantitas,
            'harga' => $request->harga,
        ]);

        return redirect()->route('pembeliandetail.index', $request->id_pembelian)->with('success', 'Pembelian detail added successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $pembeliandetail = Pembeliandetail::findOrFail($id);
        $pembeliandetail->update($request->all());

        return redirect()->route('pembeliandetail.index', $pembeliandetail->id_pembelian)->with('success', 'Pembelian detail updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pembeliandetail = Pembeliandetail::findOrFail($id);
        $pembelianId = $pembeliandetail->id_pembelian;
        $pembeliandetail->delete();

        return redirect()->route('pembeliandetail.index', $pembelianId)->with('success', 'Pembelian detail deleted successfully.');
    }
}
