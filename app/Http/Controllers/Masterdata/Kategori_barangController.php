<?php

namespace App\Http\Controllers\Masterdata;

use App\Models\Masterdata\Kategori_barang;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Kategori_barangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $id_perusahaan = Auth::user()->id_perusahaan;
        $kategori_barangs = Kategori_barang::where('id_perusahaan', $id_perusahaan)->get();
        return view('masterdata.kategori_barang.index', compact('kategori_barangs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('masterdata.kategori_barang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:255',
        ]);

        Kategori_barang::create([
            'nama' => $request->nama,
            'id_perusahaan' => Auth::user()->id_perusahaan,
        ]);

        return redirect()->route('kategori-produk.index')->with('success', 'Kategori Produk created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $kategori_barang = Kategori_barang::where('id_perusahaan', Auth::user()->id_perusahaan)
                              ->findOrFail($id);
        return view('masterdata.kategori_barang.edit', compact('kategori_barang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $request->validate([
            'nama' => 'required|max:255',
        ]);

        $kategori_barang = Kategori_barang::where('id_perusahaan', Auth::user()->id_perusahaan)
                              ->findOrFail($id);
        $kategori_barang->update([
            'nama' => $request->nama,
        ]);

        return redirect()->route('kategori-produk.index')->with('success', 'Kategori Produk updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kategori_barang = Kategori_barang::where('id_perusahaan', Auth::user()->id_perusahaan)
                              ->findOrFail($id);

        $kategori_barang->delete();

        return redirect()->route('kategori-produk.index')->with('success', 'Kategori Produk deleted successfully.');
    }
}
