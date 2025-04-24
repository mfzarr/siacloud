<?php

namespace App\Http\Controllers\Laporan;

use Illuminate\Http\Request;
use App\Models\Masterdata\Coa;
use App\Models\Laporan\JurnalUmum;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class LaporanLabaRugiController extends Controller
{
    public function calculateLabaRugi($startDate, $endDate, $id_perusahaan = null)
    {
        if (!$id_perusahaan) {
            $id_perusahaan = auth()->user()->id_perusahaan;
        }

        // Get Pendapatan (Revenue) from neraca saldo
        $pendapatan = $this->getAccountsFromNeracaSaldo($id_perusahaan, '4', $startDate, $endDate);

        // Get Biaya (Expenses) from neraca saldo
        $biaya = $this->getAccountsFromNeracaSaldo($id_perusahaan, '5', $startDate, $endDate);

        $totalPendapatan = $pendapatan->sum('saldo');
        $totalBiaya = $biaya->sum('saldo');
        $labaRugi = $totalPendapatan - $totalBiaya;

        return [
            'pendapatan' => $pendapatan,
            'biaya' => $biaya,
            'totalPendapatan' => $totalPendapatan,
            'totalBiaya' => $totalBiaya,
            'labaRugi' => $labaRugi
        ];
    }

    private function getAccountsFromNeracaSaldo($id_perusahaan, $kelompok_akun, $startDate, $endDate)
    {
        return DB::table('coa')
            ->leftJoin('jurnal_umum', function($join) use ($startDate, $endDate) {
                $join->on('coa.id_coa', '=', 'jurnal_umum.id_coa')
                     ->whereBetween('jurnal_umum.tanggal_jurnal', [$startDate, $endDate]);
            })
            ->select(
                'coa.id_coa',
                'coa.kode_akun',
                'coa.nama_akun',
                DB::raw('COALESCE(coa.saldo_awal, 0) + 
                    CASE 
                        WHEN coa.kelompok_akun IN (1, 5) THEN COALESCE(SUM(jurnal_umum.debit), 0) - COALESCE(SUM(jurnal_umum.credit), 0)
                        ELSE COALESCE(SUM(jurnal_umum.credit), 0) - COALESCE(SUM(jurnal_umum.debit), 0)
                    END as saldo')
            )
            ->where('coa.kelompok_akun', $kelompok_akun)
            ->where('coa.id_perusahaan', $id_perusahaan)
            ->whereBetween('coa.tanggal_saldo_awal', [$startDate, $endDate])
            ->groupBy('coa.id_coa', 'coa.kode_akun', 'coa.nama_akun', 'coa.saldo_awal', 'coa.kelompok_akun')
            ->orderBy('coa.kode_akun')
            ->get();
    }

    public function index(Request $request)
    {
        $id_perusahaan = auth()->user()->id_perusahaan;
        
        // Get selected month or default to current month
        $selectedMonth = $request->input('bulan', date('Y-m'));
        $date = Carbon::createFromFormat('Y-m', $selectedMonth);
        
        // Get first and last day of selected month
        $startDate = $date->copy()->startOfMonth()->format('Y-m-d');
        $endDate = $date->copy()->endOfMonth()->format('Y-m-d');

        $calculations = $this->calculateLabaRugi($startDate, $endDate, $id_perusahaan);

        return view('laporan.laba_rugi.index', [
            'pendapatan' => $calculations['pendapatan'],
            'biaya' => $calculations['biaya'],
            'totalPendapatan' => $calculations['totalPendapatan'],
            'totalBiaya' => $calculations['totalBiaya'],
            'labaRugi' => $calculations['labaRugi'],
            'selectedMonth' => $selectedMonth
        ]);
    }
}
