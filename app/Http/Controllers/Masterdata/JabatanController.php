<?php

namespace App\Http\Controllers\Masterdata;

use App\Http\Controllers\Controller;
use App\Models\Masterdata\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $id_perusahaan = Auth::user()->id_perusahaan;
        $jabatans = Jabatan::where('id_perusahaan', $id_perusahaan)->get();
        return view('masterdata.jabatan.index', compact('jabatans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('masterdata.jabatan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:255',
            'asuransi' => 'required|string',
            'tarif' => 'required|string',
        ]);
    
        // Konversi input dari format ribuan menjadi angka murni
        $asuransi = str_replace('.', '', $request->asuransi);
        $tarif = str_replace('.', '', $request->tarif);
    
        // Simpan data ke database
        Jabatan::create([
            'nama' => $request->nama,
            'asuransi' => $asuransi,
            'tarif' => $tarif,
            'id_perusahaan' => Auth::user()->id_perusahaan,
        ]);
    
        return redirect()->route('jabatan.index')->with('success', 'Jabatan created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $jabatan = Jabatan::where('id_perusahaan', Auth::user()->id_perusahaan)
            ->findOrFail($id);
        return view('masterdata.jabatan.edit', compact('jabatan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|max:255',
            'asuransi' => 'required|string',
            'tarif' => 'required|string',
        ]);
    
        // Konversi input dari format ribuan menjadi angka murni
        $asuransi = str_replace('.', '', $request->asuransi);
        $tarif = str_replace('.', '', $request->tarif);
    
        // Ambil data jabatan berdasarkan perusahaan
        $jabatan = Jabatan::where('id_perusahaan', Auth::user()->id_perusahaan)
            ->findOrFail($id);
    
        // Update data di database
        $jabatan->update([
            'nama' => $request->nama,
            'asuransi' => $asuransi,
            'tarif' => $tarif,
        ]);
    
        return redirect()->route('jabatan.index')->with('success', 'Jabatan updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $jabatan = Jabatan::where('id_perusahaan', Auth::user()->id_perusahaan)
            ->findOrFail($id);
        $jabatan->delete();

        return redirect()->route('jabatan.index')->with('success', 'Jabatan deleted successfully.');
    }
}
