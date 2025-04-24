<?php

namespace App\Http\Controllers\Masterdata;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Masterdata\Asset;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AssetController extends Controller
{
    public function index()
    {
        $id_perusahaan = Auth::user()->id_perusahaan;
        $assets = Asset::where('id_perusahaan', $id_perusahaan)->get();
        return view('masterdata.aset.index', compact('assets'));
    }

    public function create()
    {
        return view('masterdata.aset.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_asset' => 'required|max:255',
            'harga_perolehan' => 'nullable|string',
            'nilai_sisa' => 'nullable|string',
            'masa_manfaat' => 'required|integer|min:1',
            'tanggal_perolehan' => 'required|date_format:Y-m', // Ubah validasi menjadi format tahun-bulan
        ]);
    
        $harga_perolehan = str_replace('.', '', $request->harga_perolehan);
        $nilai_sisa = str_replace('.', '', $request->nilai_sisa);
    
        // Ubah tanggal perolehan menjadi awal bulan
        $tanggal_perolehan = Carbon::createFromFormat('Y-m', $request->tanggal_perolehan)->startOfMonth();
    
        Asset::create([
            'nama_asset' => $request->nama_asset,
            'harga_perolehan' => $harga_perolehan,
            'nilai_sisa' => $nilai_sisa,
            'masa_manfaat' => $request->masa_manfaat,
            'id_perusahaan' => Auth::user()->id_perusahaan,
            'tanggal_perolehan' => $tanggal_perolehan,
        ]);
    
        return redirect()->route('aset.index')->with('success', 'Asset created successfully.');
    }
    
    public function show(Asset $asset)
    {
        // Use the model's helper methods for calculations
        $penyusutan_per_tahun = $asset->depreciation_per_year;
        $schedule = $asset->calculateDepreciationSchedule();

        // Calculate total depreciation and current book value
        $total_depreciation = collect($schedule)->sum('biaya_penyusutan');
        $current_book_value = collect($schedule)->last()['nilai_buku'] ?? $asset->harga_perolehan;

        return view('aset.show', [
            'asset' => $asset,
            'penyusutan_per_tahun' => $penyusutan_per_tahun,
            'total_depreciation' => $total_depreciation,
            'current_book_value' => $current_book_value,
        ]);
    }
   public function edit($id)
    {
       $asset = Asset::findOrFail($id);
        return view('masterdata.aset.edit', compact('asset'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_asset' => 'required|max:255',
            'harga_perolehan' => 'nullable|string',
            'nilai_sisa' => 'nullable|string',
            'masa_manfaat' => 'required|integer|min:1',
            'tanggal_perolehan' => 'required|date_format:Y-m', // Ubah validasi menjadi format tahun-bulan
        ]);
    
        $aset = Asset::findOrFail($id);
    
        $harga_perolehan = str_replace('.', '', $request->harga_perolehan);
        $nilai_sisa = str_replace('.', '', $request->nilai_sisa);
    
        // Ubah tanggal perolehan menjadi awal bulan
        $tanggal_perolehan = Carbon::createFromFormat('Y-m', $request->tanggal_perolehan)->startOfMonth();
    
        $aset->update([
            'nama_asset' => $request->nama_asset,
            'harga_perolehan' => $harga_perolehan,
            'nilai_sisa' => $nilai_sisa,
            'masa_manfaat' => $request->masa_manfaat,
            'tanggal_perolehan' => $tanggal_perolehan,
        ]);
    
        return redirect()->route('aset.index')->with('success', 'Asset updated successfully.');
    }

    public function destroy($id)
    {
        $aset = Asset::where('id_assets', $id)->firstOrFail();
        $aset->delete();
        return redirect()->route('aset.index')->with('success', 'Asset deleted successfully.');
    }
    
    public function calculateDepreciation(Asset $aset, Request $request)
    {
        $schedule = $aset->calculateDepreciationSchedule();
    
        // Filter by year if a year is selected
        if ($request->has('year') && $request->year != '') {
            $year = $request->year;
            $schedule = array_filter($schedule, function ($item) use ($year) {
                return Carbon::parse($item['bulan_tahun'])->format('Y') == $year;
            });
        }
    
        // Convert 'bulan_tahun' string to date and sort by date (similar to STR_TO_DATE)
        usort($schedule, function ($a, $b) {
            // Convert 'bulan_tahun' to date using Carbon
            $dateA = Carbon::createFromFormat('F Y', $a['bulan_tahun']); // 'F Y' is for 'Month Year'
            $dateB = Carbon::createFromFormat('F Y', $b['bulan_tahun']);
            
            // Compare the dates for sorting
            return $dateA->gt($dateB) ? 1 : -1;
        });
    
        $depreciation_schedule = $schedule;
    
        return view('masterdata.aset.depreciation', [
            'asset' => $aset,
            'depreciation_schedule' => $depreciation_schedule,
            'selected_year' => $request->year,
        ]);
    }
    
    
}
