<?php

namespace App\Http\Controllers;

use App\Charts\Dashboard;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Transaksi\Penjualan;
use App\Models\Transaksi\PenjualanDetail;

class DashboardController extends Controller
{
    public function index(Request $request, Dashboard $chart)
    {
        $idPerusahaan = auth()->user()->id_perusahaan;
        
        // Handle date filter
        $dateFilter = $request->input('dateFilter', '');
        $filterType = $request->input('filterType', 'month'); // Default to month filter
        
        // Default to current month/year
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        if ($filterType === 'month' && $request->has('month')) {
            // Month filter
            $selectedDate = Carbon::parse($request->input('month'));
            $currentMonth = $selectedDate->month;
            $currentYear = $selectedDate->year;
            $startDate = $selectedDate->copy()->startOfMonth();
            $endDate = $selectedDate->copy()->endOfMonth();
        } elseif ($filterType === 'range' && !empty($dateFilter)) {
            // Date range filter
            $dates = explode(' - ', $dateFilter);
            if (count($dates) === 2) {
                $startDate = Carbon::createFromFormat('Y-m-d', $dates[0]);
                $endDate = Carbon::createFromFormat('Y-m-d', $dates[1]);
                // For chart compatibility, use the month of the start date
                $currentMonth = $startDate->month;
                $currentYear = $startDate->year;
            }
        }
        
        // Get data based on filter
        $totalPendapatan = Penjualan::whereBetween('tgl_transaksi', [$startDate, $endDate])
            ->where('id_perusahaan', $idPerusahaan)
            ->sum('total');

        $totalKuantitas = PenjualanDetail::whereHas('penjualan', function ($query) use ($startDate, $endDate, $idPerusahaan) {
            $query->whereBetween('tgl_transaksi', [$startDate, $endDate])
                  ->where('id_perusahaan', $idPerusahaan);
        })->sum('kuantitas');

        $totalSales = Penjualan::whereBetween('tgl_transaksi', [$startDate, $endDate])
            ->where('id_perusahaan', $idPerusahaan)
            ->sum(DB::raw('total - hpp'));
        
        // Generate charts with the required parameters
        // If using date range, we'll pass the start and end dates to the charts
        if ($filterType === 'range') {
            $salesChart = $chart->buildWithDateRange($startDate, $endDate, $idPerusahaan);
            $lineChart = $chart->buildLineChartWithDateRange($startDate, $endDate, $idPerusahaan);
        } else {
            $salesChart = $chart->build($currentMonth, $currentYear, $idPerusahaan);
            $lineChart = $chart->buildLineChart($currentMonth, $currentYear, $idPerusahaan);
        }
        
        return view('dashboard', compact(
            'totalPendapatan', 
            'totalKuantitas', 
            'totalSales', 
            'salesChart', 
            'lineChart',
            'dateFilter', 
            'filterType',
            'currentMonth',
            'currentYear'
        ));
    }
}