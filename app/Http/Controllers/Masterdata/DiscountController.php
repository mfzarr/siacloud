<?php

namespace App\Http\Controllers\Masterdata;

use App\Models\Masterdata\Discount;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $id_perusahaan = Auth::user()->id_perusahaan;
        $discounts = Discount::where('id_perusahaan', $id_perusahaan)->get();
        return view('masterdata.discount.index', compact('discounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('masterdata.discount.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'min_transaksi' => 'required|integer|min:1|unique:discounts',
            'discount_percentage' => 'required|integer|min:1|max:100',
        ]);
        Discount::create([
            'min_transaksi' => $request->min_transaksi,
            'discount_percentage' => $request->discount_percentage,
            'id_perusahaan' => Auth::user()->id_perusahaan,
        ]);
        return redirect()->route('diskon.index')->with('success', 'Discount created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $discount = Discount::where('id_perusahaan', Auth::user()->id_perusahaan)
                            ->findOrFail($id);
        return view('masterdata.discount.edit', compact('discount'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id_discount)
    {
        $request->validate([
            'min_transaksi' => 'required|integer|min:1|unique:discounts,min_transaksi,' . $id_discount . ',id_discount',
            'discount_percentage' => 'required|integer|min:1|max:100',
        ]);

        $discount = Discount::where('id_perusahaan', Auth::user()->id_perusahaan)
                            ->findOrFail($id_discount);
        $discount->update([
            'min_transaksi' => $request->min_transaksi,
            'discount_percentage' => $request->discount_percentage,
        ]);
        return redirect()->route('diskon.index')->with('success', 'Discount updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_discount)
    {
        $discount = Discount::where('id_perusahaan', Auth::user()->id_perusahaan)
                            ->findOrFail($id_discount);
        $discount->delete();
        return redirect()->route('diskon.index')->with('success', 'Discount deleted successfully.');
    }
}
