<?php

namespace App\Http\Controllers\Masterdata;

use App\Http\Controllers\Controller;
use App\Models\Masterdata\Supplier;
use App\Models\Masterdata\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    public function index()
    {
        $id_perusahaan = Auth::user()->id_perusahaan;
        $suppliers = Supplier::with('products')->where('id_perusahaan', $id_perusahaan)->get();
        return view('masterdata.supplier.index', compact('suppliers'));
    }

    public function create()
    {
        $products = Produk::where('id_perusahaan', Auth::user()->id_perusahaan)->get();
        return view('masterdata.supplier.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:50',
            'alamat' => 'required|max:50',
            'no_telp' => 'required|max:50',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'products' => 'array',
        ]);

        $supplier = Supplier::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_telp' => $request->no_telp,
            'status' => $request->status,
            'id_perusahaan' => Auth::user()->id_perusahaan,
        ]);

        if ($request->has('products')) {
            $supplier->products()->attach($request->products);
        }

        return redirect()->route('supplier.index')->with('success', 'Supplier created successfully.');
    }

    public function edit($id)
    {
        $supplier = Supplier::where('id_perusahaan', Auth::user()->id_perusahaan)
            ->findOrFail($id);
        $products = Produk::where('id_perusahaan', Auth::user()->id_perusahaan)->get();
        $selectedProducts = $supplier->products->pluck('id_produk')->toArray();
        return view('masterdata.supplier.edit', compact('supplier', 'products', 'selectedProducts'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|max:50',
            'alamat' => 'required|max:50',
            'no_telp' => 'required|max:50',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'products' => 'array',
        ]);

        $supplier = Supplier::where('id_perusahaan', Auth::user()->id_perusahaan)
            ->findOrFail($id);

        $supplier->update([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_telp' => $request->no_telp,
            'status' => $request->status,
        ]);

        $supplier->products()->sync($request->products ?? []);

        return redirect()->route('supplier.index')->with('success', 'Supplier updated successfully.');
    }

    public function destroy($id)
    {
        $supplier = Supplier::where('id_perusahaan', Auth::user()->id_perusahaan)
            ->findOrFail($id);
        $supplier->products()->detach();
        $supplier->delete();

        return redirect()->route('supplier.index')->with('success', 'Supplier deleted successfully.');
    }
}