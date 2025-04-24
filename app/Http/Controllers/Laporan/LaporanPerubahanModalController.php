<?php

namespace App\Http\Controllers\Laporan;

use Illuminate\Http\Request;
use App\Models\Masterdata\Coa;
use App\Models\Laporan\JurnalUmum;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class LaporanPerubahanModalController extends Controller
{
    public function index(Request $request)
    {
        $id_perusahaan = auth()->user()->id_perusahaan;
        $namaPerusahaan = auth()->user()->perusahaan->nama;
    
        $selectedMonth = $request->input('bulan', date('Y-m'));
        $date = Carbon::createFromFormat('Y-m', $selectedMonth);
        
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();
    
        // Get Modal Awal from COA
        $modalCoa = Coa::where('id_perusahaan', $id_perusahaan)
            ->whereHas('kelompokakun', function($q) {
                $q->where('kelompok_akun', '3');
            })
            ->where('nama_akun', 'like', '%modal%')
            ->whereBetween('tanggal_saldo_awal', [$startOfMonth, $endOfMonth])
            ->first();
    
        $modalAwal = $modalCoa ? $modalCoa->saldo_awal : 0;
    
        // Get Laba/Rugi using the shared calculation method
        $labaRugiController = new LaporanLabaRugiController();
        $calculations = $labaRugiController->calculateLabaRugi($startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d'), $id_perusahaan);
        
        $laba = $calculations['labaRugi'];
        $prive = 0; // Set to 0 since not implemented yet
        
        $laba_prive = $laba - $prive;
        $modal_akhir = $modalAwal + $laba_prive;
    
        return view('laporan.perubahanmodal.index', compact(
            'namaPerusahaan',
            'modalAwal',
            'laba',
            'prive',
            'laba_prive',
            'modal_akhir',
            'selectedMonth',
            'startOfMonth',
            'endOfMonth'
        ));
    }

    public function calculateModalAkhir($startOfMonth, $endOfMonth, $id_perusahaan)
    {
        $startDate = Carbon::parse($startOfMonth)->startOfMonth();
        $endDate = Carbon::parse($endOfMonth)->endOfMonth();
    
        // Get Modal Awal from COA
        $modalCoa = Coa::where('id_perusahaan', $id_perusahaan)
            ->whereHas('kelompokakun', function($q) {
                $q->where('kelompok_akun', '3');
            })
            ->where('nama_akun', 'like', '%modal%')
            ->whereBetween('tanggal_saldo_awal', [$startDate, $endDate])
            ->first();
    
        if (!$modalCoa) {
            return 0; // Atau Anda bisa mengembalikan nilai default lain yang sesuai
        }
    
        $modalAwal = $modalCoa->saldo_awal ?? 0;
    
        // Get Laba/Rugi using the shared calculation method
        $labaRugiController = new LaporanLabaRugiController();
        $calculations = $labaRugiController->calculateLabaRugi($startOfMonth, $endOfMonth, $id_perusahaan);
        
        $laba = $calculations['labaRugi'];
        $prive = 0; // Set to 0 since not implemented yet
        
        $laba_prive = $laba - $prive;
        $modal_akhir = $modalAwal + $laba_prive;
    
        return $modal_akhir;
    }
}