<?php

namespace App\Http\Controllers\Transaksi;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Masterdata\Coa;
use App\Models\Laporan\JurnalUmum;
use Illuminate\Support\Facades\DB;
use App\Models\Masterdata\Karyawan;
use App\Http\Controllers\Controller;
use App\Models\Transaksi\Penggajian;
use Illuminate\Support\Facades\Auth;

class PenggajianController extends Controller
{

    public function index(Request $request)
    {
        $id_perusahaan = Auth::user()->id_perusahaan;
        $query = Penggajian::where('id_perusahaan', $id_perusahaan)
            ->with('karyawan'); // Eager load 'karyawan'
        $month = $request->input('month');
        if ($month) {
            $query->whereMonth('tanggal_penggajian', Carbon::parse($month)->month)
                  ->whereYear('tanggal_penggajian', Carbon::parse($month)->year);
        }
        $penggajian = $query->get();
        return view('transaksi.penggajian.index', compact('penggajian','month'));
    }


    public function create()
    {
        $id_perusahaan = Auth::user()->id_perusahaan;
        $karyawan = Karyawan::where('id_perusahaan', $id_perusahaan)->get();

        return view('transaksi.penggajian.create', compact('karyawan'));
    }

    public function getTotalKehadiranByKaryawan($id)
    {
        // Count the total 'hadir' status from presensi table for the selected employee
        $total_kehadiran = DB::table('presensi')
            ->where('id_karyawan', $id)
            ->where('status', 'hadir')
            ->count();

        return response()->json(['total_kehadiran' => $total_kehadiran]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_karyawan' => 'required|exists:karyawan,id_karyawan',
            'tanggal_penggajian' => 'required|date',
            'bonus' => 'required|numeric|min:0|max:100',
            'total_service' => 'required|integer',
            'bonus_kehadiran' => 'required|integer',
            'tunjangan_makan' => 'required|integer',
            'tunjangan_jabatan' => 'required|integer',
            'lembur' => 'required|integer',
            'potongan_gaji' => 'required|integer',
            'detail_potongan' => 'nullable|string',
        ]);

        $karyawan = Karyawan::find($request->id_karyawan);
        $jabatan = $karyawan->jabatan;

        // Fetch total_kehadiran from the presensi table
        $total_kehadiran = DB::table('presensi')
            ->where('id_karyawan', $request->id_karyawan)
            ->where('status', 'hadir')
            ->count();

        $bonus_service = ($request->bonus / 100) * $request->total_service;
        $total_bonus_kehadiran = $total_kehadiran * $request->bonus_kehadiran;
        $total_gaji_bersih = $jabatan->tarif + $bonus_service + $total_bonus_kehadiran - $jabatan->asuransi + $request->tunjangan_makan + $request->tunjangan_jabatan + $request->lembur - $request->potongan_gaji;

        $date = Carbon::now()->format('Ymd');

        // Set default id to 1 if no record exists yet
        $lastTransaction = Penggajian::whereDate('created_at', Carbon::today())->orderBy('no_transaksi_gaji', 'desc')->first();
        $nextId = $lastTransaction ? intval(substr($lastTransaction->no_transaksi_gaji, -4)) + 1 : 1;
        $formattedId = str_pad($nextId, 4, '0', STR_PAD_LEFT);

        $no_transaksi_gaji = "GJ/{$date}/{$formattedId}";

        // Create Penggajian (payroll record)
        $penggajian = Penggajian::create([
            'no_transaksi_gaji' => $no_transaksi_gaji,
            'tanggal_penggajian' => $request->tanggal_penggajian,
            'id_karyawan' => $request->id_karyawan,
            'tarif' => $jabatan->tarif,
            'bonus' => $request->bonus,
            'total_service' => $request->total_service,
            'bonus_service' => $bonus_service,
            'total_kehadiran' => $total_kehadiran,
            'bonus_kehadiran' => $request->bonus_kehadiran,
            'total_bonus_kehadiran' => $total_bonus_kehadiran,
            'tunjangan_makan' => $request->tunjangan_makan,
            'tunjangan_jabatan' => $request->tunjangan_jabatan,
            'lembur' => $request->lembur,
            'potongan_gaji' => $request->potongan_gaji,
            'total_gaji_bersih' => $total_gaji_bersih,
            'id_perusahaan' => Auth::user()->id_perusahaan,
            'detail_potongan' => $request->detail_potongan,
        ]);

        // Create journal entries after successfully creating the payroll record
        $this->createJournalForPenggajian($penggajian);

        return redirect()->route('penggajian.index')->with('success', 'Penggajian created successfully.');
    }

    /**
     * Create the journal entries for the Penggajian transaction.
     */
    protected function createJournalForPenggajian(Penggajian $penggajian)
    {
        $perusahaanId = $penggajian->id_perusahaan;
        $tanggal_penggajian = Carbon::parse($penggajian->tanggal_penggajian);
    
        // Function to get the correct COA based on date
        $getCoa = function ($kodeAkun, $tanggal) use ($perusahaanId) {
            $startOfMonth = $tanggal->copy()->startOfMonth();
            $endOfMonth = $tanggal->copy()->endOfMonth();
    
            return Coa::where('kode_akun', $kodeAkun)
                ->where('id_perusahaan', $perusahaanId)
                ->whereBetween('tanggal_saldo_awal', [$startOfMonth, $endOfMonth])
                ->first();
        };
    
        // Get the COA (Chart of Accounts) for relevant accounts
        $akunGaji = $getCoa('5201', $tanggal_penggajian); // Example: Salary Expense account
        $akunKas = $getCoa('1101', $tanggal_penggajian); // Example: Cash account
    
        // Generate a unique transaction ID for this group of journal entries
        $transactionId = Str::uuid();
    
        // Prepare the transaction entries
        $transactionData = [
            'transaction_id' => $transactionId,
            'entries' => [
                [
                    'id_coa' => $akunGaji->id_coa,
                    'tanggal_jurnal' => $penggajian->tanggal_penggajian,
                    'nama_akun' => $akunGaji->nama_akun,
                    'kode_akun' => $akunGaji->kode_akun,
                    'debit' => $penggajian->total_gaji_bersih,
                    'credit' => null,
                    'transaction_id' => $transactionId,
                ],
                [
                    'id_coa' => $akunKas->id_coa,
                    'tanggal_jurnal' => $penggajian->tanggal_penggajian,
                    'nama_akun' => $akunKas->nama_akun,
                    'kode_akun' => $akunKas->kode_akun,
                    'debit' => null,
                    'credit' => $penggajian->total_gaji_bersih,
                    'transaction_id' => $transactionId,
                ]
            ]
        ];
    
        // Create journal entries for this payroll transaction
        JurnalUmum::createFromTransaction($transactionData, $perusahaanId);
    }

    public function getTarifByKaryawan($id)
    {
        $karyawan = Karyawan::with('jabatan')->find($id);
        if ($karyawan && $karyawan->jabatan) {
            return response()->json(['tarif' => $karyawan->jabatan->tarif]);
        }
        return response()->json(['tarif' => 0]);
    }

    public function getTotalServiceByKaryawan($id)
    {
        // Sum all 'total' from 'penjualan' based on 'id_pegawai' in 'penjualan_detail'
        $total_service = DB::table('penjualan_detail')
            ->join('penjualan', 'penjualan.id_penjualan', '=', 'penjualan_detail.id_penjualan')
            ->where('penjualan_detail.id_pegawai', $id)
            ->sum('penjualan.total');

        return response()->json(['total_service' => $total_service]);
    }

    public function show($id)
    {
        // Fetch Penggajian with the related Karyawan and Jabatan
        $penggajian = Penggajian::with(['karyawan.jabatan'])->findOrFail($id);

        return view('transaksi.penggajian.show', compact('penggajian'));
    }

    public function destroy($id)
    {
        $penggajian = Penggajian::findOrFail($id);
        $penggajian->delete();

        return redirect()->route('penggajian.index')->with('success', 'Penggajian deleted successfully.');
    }
}
