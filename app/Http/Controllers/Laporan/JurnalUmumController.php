<?php

namespace App\Http\Controllers\laporan;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Laporan\JurnalUmum;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Masterdata\Perusahaan;
use App\Models\Masterdata\Coa; // Assuming Coa is defined here

class JurnalUmumController extends Controller
{
    public function index(Request $request)
    {
        $id_perusahaan = Auth::user()->id_perusahaan;
        $namabulan = Carbon::now()->translatedFormat('F'); // Get the current month in full name in Indonesian

        // Get the current year and month for filtering
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;

        // Get the selected month from the request, default to the current month
        $selectedMonth = $request->input('month', $currentMonth); // Default to current month
        $selectedYear = $request->input('year', $currentYear);  // Default to current year

        // Query for JurnalUmum entries with eager loading, filtering by the selected month and year
        $query = JurnalUmum::with(['coa', 'perusahaan'])
            ->where('id_perusahaan', $id_perusahaan) // Filter by perusahaan
            ->whereYear('tanggal_jurnal', $selectedYear) // Filter by selected year
            ->whereMonth('tanggal_jurnal', $selectedMonth); // Filter by selected month

        // Get the search term and filter type from the request (this can be removed if no longer needed)
        $filter = $request->input('filter');

        // Rest of your filtering logic (this can also be removed if you don't want to use additional filters)
        if ($filter) {
            $query->where('nama_akun', 'like', '%' . $filter . '%');
        }

        $jurnals = $query->paginate(2000);

        // Get filters only for this perusahaan (you can adjust this to show more useful filters)
        $filters = JurnalUmum::where('id_perusahaan', $id_perusahaan)
            ->select('nama_akun')
            ->distinct()
            ->get();

        $groupedJurnals = $jurnals->groupBy('transaction_id');

        // Fetch perusahaan (company) details
        $perusahaan = Perusahaan::where('id_perusahaan', $id_perusahaan)->first();

        // Get list of months for the dropdown
        $months = collect([
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ]);

        return view('laporan.jurnal_umum.index', compact('jurnals', 'filters', 'groupedJurnals', 'perusahaan', 'namabulan', 'months', 'selectedMonth', 'selectedYear'));
    }

    // Display Buku Besar
    public function bukuBesar(Request $request)
    {
        $id_perusahaan = auth()->user()->id_perusahaan;

        // Get selected month and year from the request, default to current month and year
        $selectedMonth = $request->input('month', Carbon::now()->month);
        $selectedYear = $request->input('year', Carbon::now()->year);

        // Calculate the start and end date of the selected month and year
        $date = Carbon::create($selectedYear, $selectedMonth, 1);
        $startDate = $date->copy()->startOfMonth()->format('Y-m-d');
        $endDate = $date->copy()->endOfMonth()->format('Y-m-d');

        // Get COAs only for this perusahaan
        $coas = Coa::where('id_perusahaan', $id_perusahaan)->get();

        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        // Filter transactions by selected month and year
        $transactions = [];
        $saldoAwal = 0;
        $currentBalance = 0;

        $selectedAccount = $request->input('account');

        if ($selectedAccount) {
            // Get the selected Coa for this perusahaan and account
            $coa = Coa::where('id_perusahaan', $id_perusahaan)
                ->where('id_coa', $selectedAccount)
                ->first();

            if ($coa) {
                $saldoAwal = $coa->saldo_awal ?? 0;

                // Fetch journal entries for selected account and company, filtered by month and year
                $transactions = JurnalUmum::where('id_perusahaan', $id_perusahaan)
                    ->where('id_coa', $selectedAccount)
                    ->whereYear('tanggal_jurnal', $selectedYear)
                    ->whereMonth('tanggal_jurnal', $selectedMonth)
                    ->orderBy('tanggal_jurnal', 'asc')
                    ->get();

                $currentBalance = $saldoAwal;
                $isAccumulatedDepreciation = stripos($coa->nama_akun, 'akumulasi penyusutan') !== false;

                foreach ($transactions as $transaction) {
                    if ($isAccumulatedDepreciation) {
                        // For accumulated depreciation accounts, credit increases the balance
                        if ($transaction->credit) {
                            $currentBalance += $transaction->credit;
                        }
                        if ($transaction->debit) {
                            $currentBalance -= $transaction->debit;
                        }
                    } else {
                        // For other accounts, use the original logic
                        if ($coa && $coa->posisi_d_c === 'Debit') {
                            if ($transaction->debit) {
                                $currentBalance += $transaction->debit;
                            }
                            if ($transaction->credit) {
                                $currentBalance -= $transaction->credit;
                            }
                        } else {
                            if ($transaction->credit) {
                                $currentBalance += $transaction->credit;
                            }
                            if ($transaction->debit) {
                                $currentBalance -= $transaction->debit;
                            }
                        }
                    }
                }
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'coas' => $coas->map(function ($coa) {
                    return [
                        'id' => $coa->id_coa,
                        'text' => $coa->kode_akun . ' - ' . $coa->nama_akun
                    ];
                })
            ]);
        }

        // Fetch total balances - fixed query, now including month and year filtering
        $totalBalances = Coa::where('id_perusahaan', $id_perusahaan)
            ->select('coa.*')
            ->selectRaw('(SELECT COALESCE(SUM(debit), 0) FROM jurnal_umum WHERE jurnal_umum.id_coa = coa.id_coa AND tanggal_jurnal BETWEEN ? AND ?) as total_debit', [$startDate, $endDate])
            ->selectRaw('(SELECT COALESCE(SUM(credit), 0) FROM jurnal_umum WHERE jurnal_umum.id_coa = coa.id_coa AND tanggal_jurnal BETWEEN ? AND ?) as total_credit', [$startDate, $endDate])
            ->get();

        // Fetch all transactions based on id_coa for the assigned perusahaan and date range filter
        $allTransactions = JurnalUmum::with('coa')
            ->join('coa', function ($join) use ($id_perusahaan) {
                $join->on('jurnal_umum.id_coa', '=', 'coa.id_coa')
                    ->where('coa.id_perusahaan', '=', $id_perusahaan);
            })
            ->where('jurnal_umum.id_perusahaan', $id_perusahaan)
            ->whereBetween('jurnal_umum.tanggal_jurnal', [$startDate, $endDate])
            ->orderBy('coa.nama_akun', 'asc')
            ->select('jurnal_umum.*', 'coa.nama_akun as coa_nama_akun')
            ->get();

        // Calculate grand totals
        $grandTotalDebit = $allTransactions->sum('debit');
        $grandTotalCredit = $allTransactions->sum('credit');

        return view('laporan.buku_besar.index', compact(
            'coas',
            'selectedAccount',
            'transactions',
            'saldoAwal',
            'currentBalance',
            'totalBalances',
            'allTransactions',
            'grandTotalDebit',
            'grandTotalCredit',
            'selectedMonth',
            'selectedYear',
            'months'
        ));
    }

    public function neracasaldo(Request $request)
    {
        $id_perusahaan = auth()->user()->id_perusahaan;

        // Get selected month and year from the request, default to current month and year
        $selectedMonth = $request->input('month', Carbon::now()->month);
        $selectedYear = $request->input('year', Carbon::now()->year);

        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        // Calculate the start and end date of the selected month
        $startDate = Carbon::create($selectedYear, $selectedMonth, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        // Fetch total balances including saldo awal
        $totalBalances = Coa::where('id_perusahaan', $id_perusahaan)
            ->whereYear('tanggal_saldo_awal', $selectedYear)
            ->whereMonth('tanggal_saldo_awal', $selectedMonth)
            ->select('coa.*')
            ->selectRaw('
                (CASE 
                    WHEN LOWER(nama_akun) LIKE \'%akumulasi penyusutan%\' THEN 0
                    ELSE (CASE WHEN kelompok_akun IN (1, 5) THEN saldo_awal ELSE 0 END) + 
                        (SELECT COALESCE(SUM(debit), 0) FROM jurnal_umum 
                        WHERE jurnal_umum.id_coa = coa.id_coa 
                        AND tanggal_jurnal BETWEEN ? AND ?)
                END) as total_debit', [$startDate, $endDate])
            ->selectRaw('
                (CASE 
                    WHEN LOWER(nama_akun) LIKE \'%akumulasi penyusutan%\' THEN saldo_awal + 
                        (SELECT COALESCE(SUM(credit), 0) FROM jurnal_umum 
                        WHERE jurnal_umum.id_coa = coa.id_coa 
                        AND tanggal_jurnal BETWEEN ? AND ?)
                    ELSE (CASE WHEN kelompok_akun IN (2, 3, 4) THEN saldo_awal ELSE 0 END) + 
                        (SELECT COALESCE(SUM(credit), 0) FROM jurnal_umum 
                        WHERE jurnal_umum.id_coa = coa.id_coa 
                        AND tanggal_jurnal BETWEEN ? AND ?)
                END) as total_credit', [$startDate, $endDate, $startDate, $endDate])
            ->get();

        // Tambahkan kolom posisi_debit_credit dan hitung saldo debit & kredit
        $totalBalances = $totalBalances->map(function ($balance) {
            // Tentukan posisi debit/kredit berdasarkan kelompok akun
            if (stripos($balance->nama_akun, 'akumulasi') !== false) {
                $balance->saldo_kredit = $balance->total_credit - $balance->total_debit;
                $balance->saldo_debit = 0;
            } elseif (in_array($balance->kelompok_akun, [1, 5])) {
                $balance->posisi_debit_credit = 'debit';
                $balance->saldo_debit = $balance->total_debit - $balance->total_credit;
                $balance->saldo_kredit = 0;
            } else {
                $balance->posisi_debit_credit = 'kredit';
                $balance->saldo_kredit = $balance->total_credit - $balance->total_debit;
                $balance->saldo_debit = 0;
            }
            return $balance;
        });

        // Hitung total keseluruhan
        $grandTotalDebit = $totalBalances->sum('total_debit');
        $grandTotalCredit = $totalBalances->sum('total_credit');
        $grandTotalSaldoDebit = $totalBalances->sum('saldo_debit');
        $grandTotalSaldoKredit = $totalBalances->sum('saldo_kredit');

        // Check if next month's initial balance already exists
        $nextMonthStart = Carbon::create($selectedYear, $selectedMonth, 1)->addMonth()->startOfMonth();
        $nextMonthBalanceExists = Coa::where('id_perusahaan', $id_perusahaan)
            ->whereDate('tanggal_saldo_awal', $nextMonthStart)
            ->exists();

        return view('laporan.neraca_saldo.index', compact(
            'totalBalances',
            'grandTotalDebit',
            'grandTotalCredit',
            'grandTotalSaldoDebit',
            'grandTotalSaldoKredit',
            'selectedMonth',
            'selectedYear',
            'months',
            'nextMonthBalanceExists'
        ));
    }

public function createCoaFromNeracaSaldo(Request $request)
{
    $id_perusahaan = auth()->user()->id_perusahaan;
    $selectedMonth = $request->input('month', Carbon::now()->month);
    $selectedYear = $request->input('year', Carbon::now()->year);

    // Calculate start and end date of selected month
    $startDate = Carbon::create($selectedYear, $selectedMonth, 1)->startOfMonth();
    $endDate = $startDate->copy()->endOfMonth();
    $nextMonthStart = $startDate->copy()->addMonth()->startOfMonth();

    // Get COAs with saldo_awal in selected month
    $totalBalances = Coa::where('id_perusahaan', $id_perusahaan)
        ->whereYear('tanggal_saldo_awal', $selectedYear)
        ->whereMonth('tanggal_saldo_awal', $selectedMonth)
        ->get();

    foreach ($totalBalances as $balance) {
        // Calculate debit and credit totals
        $totalDebit = JurnalUmum::where('id_coa', $balance->id_coa)
            ->whereBetween('tanggal_jurnal', [$startDate, $endDate])
            ->sum('debit');

        $totalCredit = JurnalUmum::where('id_coa', $balance->id_coa)
            ->whereBetween('tanggal_jurnal', [$startDate, $endDate])
            ->sum('credit');

        // Calculate new balance
        if (stripos($balance->nama_akun, 'akumulasi penyusutan') !== false) {
            $newBalance = $balance->saldo_awal + $totalCredit - $totalDebit;
        } elseif (in_array($balance->kelompok_akun, [1, 5])) {
            $newBalance = $balance->saldo_awal + $totalDebit - $totalCredit;
        } else {
            $newBalance = $balance->saldo_awal + $totalCredit - $totalDebit;
        }

        // Check if COA for next month exists
        $existingCoa = Coa::where('id_perusahaan', $id_perusahaan)
            ->where('kode_akun', $balance->kode_akun)
            ->whereDate('tanggal_saldo_awal', $nextMonthStart)
            ->first();

        if ($existingCoa) {
            // Update existing COA
            $existingCoa->update([
                'saldo_awal' => $newBalance,
                'updated_at' => now()
            ]);
        } else {
            // Create new COA
            Coa::create([
                'id_coa' => Str::uuid(),
                'id_perusahaan' => $id_perusahaan,
                'kode_akun' => $balance->kode_akun,
                'nama_akun' => $balance->nama_akun,
                'kelompok_akun' => $balance->kelompok_akun,
                'posisi_d_c' => $balance->posisi_d_c,
                'saldo_awal' => $newBalance,
                'tanggal_saldo_awal' => $nextMonthStart,
                'status' => 'neraca',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    return redirect()->back()->with('success', 'Saldo Awal bulan selanjutnya berhasil diperbarui.');
}
    
}
