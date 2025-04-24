<?php

namespace App\Http\Controllers\Laporan;

use Illuminate\Http\Request;
use App\Models\Coa;
use App\Models\JurnalUmum;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Laporan\LaporanPerubahanModalController;
use Carbon\Carbon;

class LaporanNeracaController extends Controller
{
    public function index(Request $request)
    {
        $id_perusahaan = auth()->user()->id_perusahaan;
        
        $selectedMonth = $request->input('bulan', date('Y-m'));
        $date = Carbon::createFromFormat('Y-m', $selectedMonth);
        
        // Set locale to Indonesian
        Carbon::setLocale('id');
        
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();
        
        $namaBulan = $date->isoFormat('MMMM Y');
        $namaPerusahaan = auth()->user()->perusahaan->nama;

        // Get Current Assets (Aktiva Lancar)
        $currentAssets = $this->getCurrentAssets($id_perusahaan, $startOfMonth, $endOfMonth);
        $totalCurrentAssets = $currentAssets->sum('ending_balance');

        // Get Fixed Assets (Aktiva Tetap)
        $fixedAssets = $this->getFixedAssets($id_perusahaan, $startOfMonth, $endOfMonth);
        $totalFixedAssets = collect($fixedAssets)->sum('net_value');

        // Get Current Liabilities (Hutang Jangka Pendek)
        $currentLiabilities = $this->getCurrentLiabilities($id_perusahaan, $startOfMonth, $endOfMonth);
        $totalCurrentLiabilities = $currentLiabilities->sum('ending_balance');

        // Get Equity (Modal)
        $labaRugi = 0; // You need to calculate or retrieve the actual value of $labaRugi
        $equity = $this->getEquity($id_perusahaan, $startOfMonth, $endOfMonth, $labaRugi);
        $totalEquity = $equity->sum('ending_balance');

        // Calculate total assets and total liabilities + equity
        $totalAssets = $totalCurrentAssets + $totalFixedAssets;
        $totalLiabilitiesEquity = $totalCurrentLiabilities + $totalEquity;

        return view('laporan.neraca.index', compact(
            'currentAssets',
            'fixedAssets',
            'currentLiabilities',
            'equity',
            'totalCurrentAssets',
            'totalFixedAssets',
            'totalCurrentLiabilities',
            'totalEquity',
            'totalAssets',
            'totalLiabilitiesEquity',
            'selectedMonth',
            'namaBulan',
            'namaPerusahaan'
        ));
    }

    private function getCurrentAssets($idPerusahaan, $startOfMonth, $endOfMonth)
    {
        return DB::table('coa')
            ->leftJoin('jurnal_umum', function($join) use ($startOfMonth, $endOfMonth) {
                $join->on('coa.id_coa', '=', 'jurnal_umum.id_coa')
                     ->whereBetween('jurnal_umum.tanggal_jurnal', [$startOfMonth, $endOfMonth]);
            })
            ->select(
                'coa.id_coa',
                'coa.kode_akun',
                'coa.nama_akun',
                DB::raw('COALESCE(coa.saldo_awal, 0) + 
                    COALESCE(SUM(jurnal_umum.debit), 0) - 
                    COALESCE(SUM(jurnal_umum.credit), 0) as ending_balance')
            )
            ->where('coa.kelompok_akun', 1)
            ->where('coa.id_perusahaan', $idPerusahaan)
            ->where('coa.kode_akun', 'like', '11%')
            ->whereBetween('coa.tanggal_saldo_awal', [$startOfMonth, $endOfMonth])
            ->groupBy('coa.id_coa', 'coa.kode_akun', 'coa.nama_akun', 'coa.saldo_awal')
            ->orderBy('coa.kode_akun')
            ->get();
    }

    private function getFixedAssets($idPerusahaan, $startOfMonth, $endOfMonth)
    {
        $fixedAssets = DB::table('coa as c1')
            ->leftJoin('jurnal_umum as j1', function ($join) use ($startOfMonth, $endOfMonth) {
                $join->on('c1.id_coa', '=', 'j1.id_coa')
                    ->whereBetween('j1.tanggal_jurnal', [$startOfMonth, $endOfMonth]);
            })
            ->leftJoin('coa as c2', function ($join) {
                $join->on(DB::raw('CONCAT(LEFT(c1.kode_akun, 3), "99")'), '=', 'c2.kode_akun')
                    ->where('c2.id_perusahaan', '=', DB::raw('c1.id_perusahaan'));
            })
            ->leftJoin('jurnal_umum as j2', function ($join) use ($startOfMonth, $endOfMonth) {
                $join->on('c2.id_coa', '=', 'j2.id_coa')
                    ->whereBetween('j2.tanggal_jurnal', [$startOfMonth, $endOfMonth]);
            })
            ->select(
                'c1.id_coa',
                'c1.kode_akun',
                'c1.nama_akun',
                DB::raw('COALESCE(c1.saldo_awal, 0) + 
                    COALESCE(SUM(j1.debit), 0) - 
                    COALESCE(SUM(j1.credit), 0) as asset_value'),
                DB::raw('COALESCE(c2.saldo_awal, 0) + 
                    COALESCE(SUM(j2.debit), 0) - 
                    COALESCE(SUM(j2.credit), 0) as accumulated_depreciation'),
                DB::raw('(COALESCE(c1.saldo_awal, 0) + 
                    COALESCE(SUM(j1.debit), 0) - 
                    COALESCE(SUM(j1.credit), 0)) -
                    (COALESCE(c2.saldo_awal, 0) + 
                    COALESCE(SUM(j2.debit), 0) - 
                    COALESCE(SUM(j2.credit), 0)) as net_value')
            )
            ->where('c1.kelompok_akun', 1)
            ->where('c1.id_perusahaan', $idPerusahaan)
            ->where('c1.kode_akun', 'like', '12%')
            ->whereRaw('RIGHT(c1.kode_akun, 2) != "99"')
            ->whereBetween('c1.tanggal_saldo_awal', [$startOfMonth, $endOfMonth])

            ->groupBy(
                'c1.id_coa',
                'c1.kode_akun',
                'c1.nama_akun',
                'c1.saldo_awal',
                'c2.saldo_awal'
            )
            ->orderBy('c1.kode_akun')
            ->get()
            ->map(function ($item) {
                if (stripos($item->nama_akun, 'akumulasi') !== false) {
                    $item->net_value = -abs($item->net_value);
                }
                return $item;
            });
    
        return $fixedAssets;
    }

    private function getCurrentLiabilities($idPerusahaan, $startOfMonth, $endOfMonth)
    {
        return DB::table('coa')
            ->leftJoin('jurnal_umum', function($join) use ($startOfMonth, $endOfMonth) {
                $join->on('coa.id_coa', '=', 'jurnal_umum.id_coa')
                     ->whereBetween('jurnal_umum.tanggal_jurnal', [$startOfMonth, $endOfMonth]);
            })
            ->select(
                'coa.id_coa',
                'coa.kode_akun',
                'coa.nama_akun',
                DB::raw('COALESCE(coa.saldo_awal, 0) - 
                    COALESCE(SUM(jurnal_umum.debit), 0) + 
                    COALESCE(SUM(jurnal_umum.credit), 0) as ending_balance')
            )
            ->where('coa.kelompok_akun', 2)
            ->where('coa.id_perusahaan', $idPerusahaan)
            ->where('coa.kode_akun', 'like', '21%')
            ->whereBetween('coa.tanggal_saldo_awal', [$startOfMonth, $endOfMonth])
            ->groupBy('coa.id_coa', 'coa.kode_akun', 'coa.nama_akun', 'coa.saldo_awal')
            ->orderBy('coa.kode_akun')
            ->get();
    }

    private function getEquity($idPerusahaan, $startOfMonth, $endOfMonth, $labaRugi)
    {
        $perubahanModalController = new LaporanPerubahanModalController();
        $modalAkhir = $perubahanModalController->calculateModalAkhir($startOfMonth, $endOfMonth, $idPerusahaan);

        $equity = collect([
            (object)[
                'id_coa' => null,
                'kode_akun' => '31',
                'nama_akun' => 'Modal Akhir',
                'ending_balance' => $modalAkhir
            ],
            // (object)[
            //     'id_coa' => null,
            //     'kode_akun' => '-',
            //     'nama_akun' => 'Laba/Rugi',
            //     'ending_balance' => $labaRugi
            // ]
        ]);

        return $equity;
    }
}