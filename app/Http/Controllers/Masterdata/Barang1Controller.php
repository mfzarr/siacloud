<?php

namespace App\Http\Controllers\Masterdata;

use App\Models\Masterdata\Barang1;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class Barang1Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $id_perusahaan = Auth::user()->id_perusahaan;
        $barang1 = Barang1::where('id_perusahaan', $id_perusahaan)->get();
        return view('masterdata.barang.index', compact('barang1'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('masterdata.barang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:255',
            // 'detail' => 'required|max:255',
            // 'satuan' => 'required|max:255',
            'kategori' => 'required|max:255',
        ]);

        Barang1::create([
            'nama' => $request->nama,
            // 'detail' => $request->detail,
            // 'satuan' => $request->satuan,
            'kategori' => $request->kategori,
            'id_perusahaan' => Auth::user()->id_perusahaan,
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang1 created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Barang1 $barang1)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $barang1 = Barang1::where('id_perusahaan', Auth::user()->id_perusahaan)
                          ->findOrFail($id);
        return view('masterdata.barang.edit', compact('barang1'));
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|max:255',
            // 'detail' => 'required|max:255',
            // 'satuan' => 'required|max:255',
            'kategori' => 'required|max:255',
        ]);

        $barang1 = Barang1::where('id_perusahaan', Auth::user()->id_perusahaan)
            ->findOrFail($id);
        $barang1->update([
            'nama' => $request->nama,
            // 'detail' => $request->detail,
            // 'satuan' => $request->satuan,
            'kategori' => $request->kategori,
        ]);
        return redirect()->route('barang.index')->with('success', 'Barang1 updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $barang1 = Barang1::where('id_perusahaan', Auth::user()->id_perusahaan)
            ->findOrFail($id);
        $barang1->delete();
        return redirect()->route('barang.index')->with('success', 'Barang1 deleted successfully.');
    }
}
