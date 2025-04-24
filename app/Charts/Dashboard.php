<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use App\Models\Transaksi\PenjualanDetail;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class Dashboard
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build($currentMonth, $currentYear, $idPerusahaan): \ArielMejiaDev\LarapexCharts\PieChart
    {
        // Get sales data filtered by month, year and company
        $salesDetails = PenjualanDetail::whereHas('penjualan', function ($query) use ($currentMonth, $currentYear, $idPerusahaan) {
            $query->whereMonth('tgl_transaksi', $currentMonth)
                  ->whereYear('tgl_transaksi', $currentYear)
                  ->where('id_perusahaan', $idPerusahaan);
        })->with('produkRelation')->get();
        
        return $this->generateChart($salesDetails, "Top 5 Produk Terjual - " . Carbon::createFromDate($currentYear, $currentMonth, 1)->format('F Y'));
    }
    
    public function buildWithDateRange(Carbon $startDate, Carbon $endDate, $idPerusahaan): \ArielMejiaDev\LarapexCharts\PieChart
    {
        // Get sales data filtered by date range and company
        $salesDetails = PenjualanDetail::whereHas('penjualan', function ($query) use ($startDate, $endDate, $idPerusahaan) {
            $query->whereBetween('tgl_transaksi', [$startDate, $endDate])
                  ->where('id_perusahaan', $idPerusahaan);
        })->with('produkRelation')->get();
        
        return $this->generateChart($salesDetails, "Top 5 Produk Terjual - " . $startDate->format('d M Y') . " s/d " . $endDate->format('d M Y'));
    }
    
    private function generateChart($salesDetails, $title): \ArielMejiaDev\LarapexCharts\PieChart
    {
        // Group by product and sum the quantities
        $productSales = $salesDetails->groupBy('produkRelation.nama')
            ->map(function (Collection $group) {
                return $group->sum('kuantitas'); // Changed from 'total' to 'kuantitas'
            })
            ->sortByDesc(function ($quantity) {
                return $quantity;
            });
        
        // Get top 5 products
        $topProducts = $productSales->take(5);
        
        // Extract quantities and product names
        $quantities = $topProducts->values()->toArray();
        $labels = $topProducts->keys()->toArray();
        
        // Create pie chart with the data
        return $this->chart->pieChart()
            ->setTitle($title)
            ->addData($quantities)
            ->setLabels($labels)
            ->setColors(['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'])
            ->setDataLabels(true)
            ->setOptions([
                'dataLabels' => [
                    'enabled' => true,
                    'formatter' => 'function(val, opt) { return opt.w.globals.seriesNames[opt.seriesIndex] + ": " + val.toFixed(1) + "%" }',
                ],
                'legend' => [
                    'position' => 'bottom',
                ],
                'plotOptions' => [
                    'pie' => [
                        'donut' => [
                            'labels' => [
                                'show' => true,
                                'name' => [
                                    'show' => true,
                                ],
                                'value' => [
                                    'show' => true,
                                    'formatter' => 'function (val) { return val + " unit" }', // Changed to show units instead of currency
                                ],
                                'total' => [
                                    'show' => true,
                                    'formatter' => 'function (w) { 
                                        return "Total: " + w.globals.seriesTotals.reduce((a, b) => { 
                                            return a + b 
                                        }, 0) + " unit"
                                    }', // Changed to show units instead of currency
                                ]
                            ]
                        ]
                    ]
                ],
                'tooltip' => [
                    'y' => [
                        'formatter' => 'function(value) { return value + " unit" }' // Changed to show units instead of currency
                    ]
                ]
            ]);
    }

    public function buildLineChart($currentMonth, $currentYear, $idPerusahaan): \ArielMejiaDev\LarapexCharts\LineChart
    {
        // Get daily sales data for the selected month
        $startDate = Carbon::createFromDate($currentYear, $currentMonth, 1)->startOfDay();
        $endDate = Carbon::createFromDate($currentYear, $currentMonth, 1)->endOfMonth()->endOfDay();
        
        $salesData = \App\Models\Transaksi\Penjualan::select(
                \Illuminate\Support\Facades\DB::raw('DATE(tgl_transaksi) as date'),
                \Illuminate\Support\Facades\DB::raw('SUM(total) as total_sales')
            )
            ->whereBetween('tgl_transaksi', [$startDate, $endDate])
            ->where('id_perusahaan', $idPerusahaan)
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Prepare data for chart
        $daysInMonth = $endDate->day;
        $labels = [];
        $values = [];
        
        // Initialize with zeros
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::createFromDate($currentYear, $currentMonth, $day)->format('Y-m-d');
            $labels[] = $day; // Just show the day number
            $values[] = 0;
        }
        
        // Fill in actual data
        foreach ($salesData as $data) {
            $day = Carbon::parse($data->date)->day;
            $values[$day - 1] = (float) $data->total_sales;
        }
        
        return $this->chart->lineChart()
            ->setTitle('Tren Penjualan - ' . Carbon::createFromDate($currentYear, $currentMonth, 1)->format('F Y'))
            ->addData('Total Penjualan', $values)
            ->setXAxis($labels)
            ->setColors(['#4680ff'])
            ->setGrid()
            ->setDataLabels(false)
            ->setHeight(350)
            ->setOptions([
                'stroke' => [
                    'curve' => 'straight',
                    'width' => 2,
                ],
                'tooltip' => [
                    'y' => [
                        'formatter' => 'function(value) { 
                            return new Intl.NumberFormat("id-ID", { 
                                style: "currency", 
                                currency: "IDR",
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            }).format(value);
                        }'
                    ]
                ],
                'yaxis' => [
                    'labels' => [
                        'formatter' => 'function(val) { 
                            if(val >= 1000000) {
                                return "Rp " + (val/1000000).toFixed(1) + " Jt";
                            } else if(val >= 1000) {
                                return "Rp " + (val/1000).toFixed(1) + " Rb";
                            } else {
                                return "Rp " + val;
                            }
                        }'
                    ]
                ]
            ]);
    }
    
    public function buildLineChartWithDateRange(Carbon $startDate, Carbon $endDate, $idPerusahaan): \ArielMejiaDev\LarapexCharts\LineChart
    {
        $diffInDays = $startDate->diffInDays($endDate);
        
        // For date ranges longer than 31 days, group by week
        if ($diffInDays > 31) {
            return $this->buildWeeklyLineChart($startDate, $endDate, $idPerusahaan);
        }
        
        // For shorter ranges, show daily data
        $salesData = \App\Models\Transaksi\Penjualan::select(
                \Illuminate\Support\Facades\DB::raw('DATE(tgl_transaksi) as date'),
                \Illuminate\Support\Facades\DB::raw('SUM(total) as total_sales')
            )
            ->whereBetween('tgl_transaksi', [$startDate, $endDate])
            ->where('id_perusahaan', $idPerusahaan)
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Prepare data for chart
        $labels = [];
        $values = [];
        
        // Create a map of all dates in the range
        $currentDate = clone $startDate;
        $dateMap = [];
        
        while ($currentDate <= $endDate) {
            $dateString = $currentDate->format('Y-m-d');
            $labels[] = $currentDate->format('d M');
            $dateMap[$dateString] = 0;
            $currentDate->addDay();
        }
        
        // Fill in actual data
        foreach ($salesData as $data) {
            $dateMap[$data->date] = (float) $data->total_sales;
        }
        
        $values = array_values($dateMap);
        
        return $this->chart->lineChart()
            ->setTitle('Tren Penjualan - ' . $startDate->format('d M Y') . " s/d " . $endDate->format('d M Y'))
            ->addData('Total Penjualan', $values)
            ->setXAxis($labels)
            ->setColors(['#4680ff'])
            ->setGrid()
            ->setDataLabels(false)
            ->setHeight(350)
            ->setOptions([
                'stroke' => [
                    'curve' => 'straight',
                    'width' => 2,
                ],
                'tooltip' => [
                    'y' => [
                        'formatter' => 'function(value) { 
                            return new Intl.NumberFormat("id-ID", { 
                                style: "currency", 
                                currency: "IDR",
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            }).format(value);
                        }'
                    ]
                ],
                'yaxis' => [
                    'labels' => [
                        'formatter' => 'function(val) { 
                            if(val >= 1000000) {
                                return "Rp " + (val/1000000).toFixed(1) + " Jt";
                            } else if(val >= 1000) {
                                return "Rp " + (val/1000).toFixed(1) + " Rb";
                            } else {
                                return "Rp " + val;
                            }
                        }'
                    ]
                ]
            ]);
    }
    
    private function buildWeeklyLineChart(Carbon $startDate, Carbon $endDate, $idPerusahaan): \ArielMejiaDev\LarapexCharts\LineChart
    {
        // Group by week for longer date ranges
        $salesData = \App\Models\Transaksi\Penjualan::select(
                \Illuminate\Support\Facades\DB::raw('YEAR(tgl_transaksi) as year'),
                \Illuminate\Support\Facades\DB::raw('WEEK(tgl_transaksi) as week'),
                \Illuminate\Support\Facades\DB::raw('MIN(tgl_transaksi) as week_start'),
                \Illuminate\Support\Facades\DB::raw('SUM(total) as total_sales')
            )
            ->whereBetween('tgl_transaksi', [$startDate, $endDate])
            ->where('id_perusahaan', $idPerusahaan)
            ->groupBy('year', 'week')
            ->orderBy('year')
            ->orderBy('week')
            ->get();
        
        $labels = [];
        $values = [];
        
        foreach ($salesData as $data) {
            $weekStart = Carbon::parse($data->week_start);
            $labels[] = $weekStart->format('d M') . ' - ' . $weekStart->addDays(6)->format('d M');
            $values[] = (float) $data->total_sales;
        }
        
        return $this->chart->lineChart()
            ->setTitle('Tren Penjualan Mingguan - ' . $startDate->format('d M Y') . " s/d " . $endDate->format('d M Y'))
            ->addData('Total Penjualan', $values)
            ->setXAxis($labels)
            ->setColors(['#4680ff'])
            ->setGrid()
            ->setDataLabels(false)
            ->setHeight(350)
            ->setOptions([
                'stroke' => [
                    'curve' => 'straight',
                    'width' => 2,
                ],
                'tooltip' => [
                    'y' => [
                        'formatter' => 'function(value) { 
                            return new Intl.NumberFormat("id-ID", { 
                                style: "currency", 
                                currency: "IDR",
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            }).format(value);
                        }'
                    ]
                ],
                'yaxis' => [
                    'labels' => [
                        'formatter' => 'function(val) { 
                            if(val >= 1000000) {
                                return "Rp " + (val/1000000).toFixed(1) + " Jt";
                            } else if(val >= 1000) {
                                return "Rp " + (val/1000).toFixed(1) + " Rb";
                            } else {
                                return "Rp " + val;
                            }
                        }'
                    ]
                ]
            ]);
    }
}