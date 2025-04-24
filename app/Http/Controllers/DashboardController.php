<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Transaksi\Penjualan;
use App\Models\Transaksi\PenjualanDetail;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $idPerusahaan = auth()->user()->id_perusahaan;

        $totalPendapatan = Penjualan::whereMonth('tgl_transaksi', $currentMonth)
            ->whereYear('tgl_transaksi', $currentYear)
            ->where('id_perusahaan', $idPerusahaan)
            ->sum('total');

        $totalKuantitas = PenjualanDetail::whereHas('penjualan', function ($query) use ($currentMonth, $currentYear, $idPerusahaan) {
            $query->whereMonth('tgl_transaksi', $currentMonth)
                  ->whereYear('tgl_transaksi', $currentYear)
                  ->where('id_perusahaan', $idPerusahaan);
        })->sum('kuantitas');

        $totalSales = Penjualan::whereMonth('tgl_transaksi', $currentMonth)
            ->whereYear('tgl_transaksi', $currentYear)
            ->where('id_perusahaan', $idPerusahaan)
            ->sum(DB::raw('total - hpp'));
        
        return view('dashboard', compact('totalPendapatan', 'totalKuantitas', 'totalSales'));
    }
}