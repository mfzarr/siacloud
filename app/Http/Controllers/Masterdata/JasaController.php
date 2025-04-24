<?php

namespace App\Http\Controllers\Masterdata;

use App\Http\Controllers\Controller;
use App\Models\Masterdata\Jasa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JasaController extends Controller
{
    public function index()
    {
        $id_perusahaan = Auth::user()->id_perusahaan;
        $jasas = Jasa::where('id_perusahaan', $id_perusahaan)->get();
        return view('masterdata.jasa.index', compact('jasas'));
    }

    public function create()
    {
        return view('masterdata.jasa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:50',
            'detail' => 'required',
            'harga' => 'required|integer|min:0',
        ]);

        Jasa::create([
            'nama' => $request->nama,
            'detail' => $request->detail,
            'harga' => $request->harga,
            'id_perusahaan' => Auth::user()->id_perusahaan,
        ]);

        return redirect()->route('jasa.index')->with('success', 'Jasa created successfully.');
    }

    public function edit($id)
    {
        $jasa = Jasa::where('id_perusahaan', Auth::user()->id_perusahaan)->findOrFail($id);
        return view('masterdata.jasa.edit', compact('jasa'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|max:50',
            'detail' => 'required',
            'harga' => 'required|integer|min:0',
        ]);

        $jasa = Jasa::where('id_perusahaan', Auth::user()->id_perusahaan)->findOrFail($id);

        $jasa->update([
            'nama' => $request->nama,
            'detail' => $request->detail,
            'harga' => $request->harga,
        ]);

        return redirect()->route('jasa.index')->with('success', 'Jasa updated successfully.');
    }

    public function destroy($id)
    {
        $jasa = Jasa::where('id_perusahaan', Auth::user()->id_perusahaan)->findOrFail($id);
        $jasa->delete();

        return redirect()->route('jasa.index')->with('success', 'Jasa deleted successfully.');
    }
}
