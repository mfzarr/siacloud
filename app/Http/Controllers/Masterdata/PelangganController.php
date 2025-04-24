<?php

namespace App\Http\Controllers\Masterdata;

use App\Http\Controllers\Controller;
use App\Models\Masterdata\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelangganController extends Controller
{
    public function index()
    {
        $id_perusahaan = Auth::user()->id_perusahaan;
        $pelanggans = Pelanggan::where('id_perusahaan', $id_perusahaan)->get();
        return view('masterdata.pelanggan.index', compact('pelanggans'));
    }

    public function create()
    {
        return view('masterdata.pelanggan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:255',
            'email' => 'nullable|email|max:255',
            'no_telp' => 'required|max:255',
            'alamat' => 'required|max:255',
            'tgl_daftar' => 'required|date',
            'jenis_kelamin' => 'required|in:Pria,Wanita',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ]);

        Pelanggan::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tgl_daftar' => $request->tgl_daftar,
            'status' => $request->status,
            'id_perusahaan' => Auth::user()->id_perusahaan,
        ]);

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan created successfully.');
    }

    public function edit($id)
    {
        $pelanggan = Pelanggan::where('id_perusahaan', Auth::user()->id_perusahaan)
                              ->findOrFail($id);
        return view('masterdata.pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|max:255',
            'email' => 'nullable|email|max:255',
            'no_telp' => 'required|max:255',
            'alamat' => 'required|max:255',
            'tgl_daftar' => 'required|date',
            'jenis_kelamin' => 'required|in:Pria,Wanita',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ]);

        $pelanggan = Pelanggan::where('id_perusahaan', Auth::user()->id_perusahaan)
                              ->findOrFail($id);

        $pelanggan->update([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tgl_daftar' => $request->tgl_daftar,
            'status' => $request->status,
        ]);

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan updated successfully.');
    }

    public function destroy($id)
    {
        $pelanggan = Pelanggan::where('id_perusahaan', Auth::user()->id_perusahaan)
                              ->findOrFail($id);
        $pelanggan->delete();

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan deleted successfully.');
    }
}
