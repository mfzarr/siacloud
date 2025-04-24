<?php

namespace App\Http\Controllers\laporan;

use App\Models\Laporan\JurnalUmum;
use App\Models\Masterdata\Coa; // Assuming Coa is defined here
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class JurnalUmumController extends Controller
{
    // Display all journal entries with filtering and pagination
    public function index(Request $request)
    {
        // Get the search term and filter type from the request
        $search = $request->input('search');
        $filter = $request->input('filter');
        $month = $request->input('month');
        $id_perusahaan = Auth::user()->id_perusahaan;
        
        // Query for JurnalUmum entries with eager loading
        $query = JurnalUmum::with(['coa', 'perusahaan'])
            ->where('id_perusahaan', $id_perusahaan); // Filter by id_perusahaan

        // Filter by search term
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_akun', 'like', '%' . $search . '%')
                  ->orWhere('kode_akun', 'like', '%' . $search . '%');
            });
        }

        // Filter by month
        // if ($month) {
        //     $query->whereMonth('tanggal_jurnal', $month);
        // }

        // Filter by type (you may adjust the filtering logic according to your needs)
        if ($filter) {
            $query->where('nama_akun', 'like', '%' . $filter . '%');  // Changed to 'like' for flexibility
        }

        // Fetch the paginated, filtered, and grouped journal entries
        // Paginate results (adjust the number of items per page as needed)
        $jurnals = $query->paginate(50);

        // Get distinct nama_akun values for the filter dropdown
        $filters = JurnalUmum::select('nama_akun')->distinct()->get();

        $month = JurnalUmum::selectRaw('MONTH(tanggal_jurnal) as month')
            ->distinct()
            ->get();

        // Group the results by 'transaction_id' (or whatever field pairs the debit and credit transactions)
        $groupedJurnals = $jurnals->groupBy('transaction_id'); // assuming 'transaction_id' exists and is used for pairing

        // Pass the filtered, paginated, and grouped results to the view
        return view('laporan.jurnal_umum.index', compact('jurnals', 'filters', 'groupedJurnals', 'month'));
    }

    // Display Buku Besar
    public function bukuBesar(Request $request)
    {
        // Get the authenticated user's company ID
        $id_perusahaan = Auth::user()->id_perusahaan;

        // Get all COA for dropdown, filtered by company ID
        $coas = Coa::where('id_perusahaan', $id_perusahaan)->get();

        // Get the selected account
        $selectedAccount = $request->input('account');
        $transactions = [];
        $saldoAwal = 0;
        $currentBalance = 0;

        if ($selectedAccount) {
            // Fetch the COA record for the selected account
            $coa = Coa::where('id_perusahaan', $id_perusahaan)->find($selectedAccount);

            if ($coa) {
                // Get the starting saldo_awal from the Coa table
                $saldoAwal = $coa->saldo_awal;

                // Fetch all journal entries for the selected account, filtered by company ID
                $transactions = JurnalUmum::where('kode_akun', $coa->kode_akun)
                    ->where('id_perusahaan', $id_perusahaan)
                    ->orderBy('tanggal_jurnal', 'asc')
                    ->get();

                // Initialize current balance with saldo_awal
                $currentBalance = $saldoAwal;

                // Fetch the account type (posisi_debit_credit) from TipeAkun
                $tipeAkun = $coa->tipeAkun;

                // Calculate the balance by iterating through the journal entries
                foreach ($transactions as $transaction) {
                    if ($tipeAkun && $tipeAkun->posisi_debit_credit === 'debit') {
                        // If posisi_debit_credit is 'debit', debit increases the balance
                        if ($transaction->debit) {
                            $currentBalance += $transaction->debit;
                        }
                        if ($transaction->credit) {
                            $currentBalance -= $transaction->credit;
                        }
                    } else {
                        // If posisi_debit_credit is 'credit', credit increases the balance
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

        // Fetch total balances of all accounts, filtered by company ID, sum debit and credit
        $totalBalances = Coa::where('id_perusahaan', $id_perusahaan)
            ->withSum('jurnalUmums as total_debit', 'debit')
            ->withSum('jurnalUmums as total_credit', 'credit')
            ->get();

        // Fetch all transactions for View 3, filtered by company ID, and order by account name (nama_akun)
        $allTransactions = JurnalUmum::with('coa')
            ->join('coa', 'jurnal_umum.kode_akun', '=', 'coa.kode_akun')
            ->where('jurnal_umum.id_perusahaan', $id_perusahaan)
            ->orderBy('coa.nama_akun', 'asc') // Order by account name
            ->get();

        // Sum up the grand totals for debit and credit
        $grandTotalDebit = $allTransactions->sum('debit');
        $grandTotalCredit = $allTransactions->sum('credit');

        // Return the view with all the required data
        return view('laporan.buku_besar.index', compact(
            'coas',
            'selectedAccount',
            'transactions',
            'saldoAwal',
            'currentBalance',
            'totalBalances',
            'allTransactions',
            'grandTotalDebit',
            'grandTotalCredit'
        ));
    }
}