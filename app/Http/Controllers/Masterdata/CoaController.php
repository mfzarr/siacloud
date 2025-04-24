<?php

namespace App\Http\Controllers\Masterdata;

use Illuminate\Http\Request;
use App\Models\Masterdata\Coa;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Masterdata\CoaKelompok;

class CoaController extends Controller
{
    /**
     * Display a listing of the COA.
     */
    public function index(Request $request)
    {
        $id_perusahaan = Auth::user()->id_perusahaan;

        // Get the selected month from the request, default to current month
        $selectedMonth = $request->input('month', Carbon::now()->format('Y-m'));

        $query = Coa::where('id_perusahaan', $id_perusahaan);

        // Apply filter if month is selected
        if ($selectedMonth) {
            $query->whereRaw("DATE_FORMAT(tanggal_saldo_awal, '%Y-%m') = ?", [$selectedMonth]);
        }

        $coas = $query->get();

        return view('masterdata.coa.index', compact('coas', 'selectedMonth'));
    }

    /**
     * Store a newly created COA in the database.
     */
    public function create()
    {
        $id_perusahaan = Auth::user()->id_perusahaan;
        $kelompokakun = CoaKelompok::where('id_perusahaan', $id_perusahaan)->get();
        return view('masterdata.coa.create', compact('kelompokakun'));
    }

    public function store(Request $request)
    {
        // Ubah format tanggal menjadi tanggal 1 pada bulan yang dipilih
        $tanggal_saldo_awal = $request->tanggal_saldo_awal
            ? Carbon::createFromFormat('Y-m', $request->tanggal_saldo_awal)->startOfMonth()->format('Y-m-d')
            : null;

        $request->validate([
            'kode_akun' => [
                'required',
                'numeric',
                'digits_between:1,4',
                function ($attribute, $value, $fail) use ($tanggal_saldo_awal) {
                    $exists = Coa::where('kode_akun', $value)
                        ->where('id_perusahaan', Auth::user()->id_perusahaan)
                        ->where('tanggal_saldo_awal', $tanggal_saldo_awal)
                        ->exists();
                    if ($exists) {
                        $fail('Kode akun sudah digunakan');
                    }
                },
            ],
            'nama_akun' => 'required|string|max:255',
            'kelompok_akun' => 'required|integer|exists:coa_kelompok,id_kelompok_akun',
            'tanggal_saldo_awal' => 'nullable|date_format:Y-m',
            'posisi_d_c' => 'required|string|max:255',
            'saldo_awal' => 'required|numeric',
            'status' => 'nullable|string',
        ]);

        Coa::create([
            'kode_akun' => $request->kode_akun,
            'nama_akun' => $request->nama_akun,
            'kelompok_akun' => $request->kelompok_akun,
            'posisi_d_c' => $request->posisi_d_c,
            'tanggal_saldo_awal' => $tanggal_saldo_awal,
            'saldo_awal' => $request->saldo_awal,
            'id_perusahaan' => Auth::user()->id_perusahaan,
        ]);

        // Redirect to the index with the selected month filter
        $redirectMonth = $request->tanggal_saldo_awal ?? Carbon::now()->format('Y-m');
        return redirect()->route('coa.index', ['month' => $redirectMonth])
            ->with('success', 'COA berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified COA record.
     */
    public function edit($id)
    {
        $id_perusahaan = Auth::user()->id_perusahaan;
        $coas = Coa::where('id_coa', $id)->firstOrFail();
        $kelompokakun = CoaKelompok::where('id_perusahaan', $id_perusahaan)->get();
        return view('masterdata.coa.edit', compact('coas', 'kelompokakun'));
    }

    /**
     * Update the specified COA record.
     */
    public function update(Request $request, $id)
    {
        $coa = Coa::where('id_coa', $id)->firstOrFail();

        $tanggal_saldo_awal = $request->tanggal_saldo_awal
            ? Carbon::createFromFormat('Y-m', $request->tanggal_saldo_awal)->startOfMonth()->format('Y-m-d')
            : null;

        $request->validate([
            'kode_akun' => 'required|numeric|digits_between:1,4',
            'nama_akun' => 'required|string|max:255',
            'kelompok_akun' => 'required|integer|exists:coa_kelompok,id_kelompok_akun',
            'posisi_d_c' => 'required|string|max:255',
            'tanggal_saldo_awal' => 'required|date_format:Y-m',
            'saldo_awal' => 'required|numeric',
        ]);

        $coa->update([
            'kode_akun' => $request->kode_akun,
            'nama_akun' => $request->nama_akun,
            'kelompok_akun' => $request->kelompok_akun,
            'posisi_d_c' => $request->posisi_d_c,
            'tanggal_saldo_awal' => $tanggal_saldo_awal,
            'saldo_awal' => $request->saldo_awal,
        ]);

        // Redirect to the index with the selected month filter
        $redirectMonth = $request->tanggal_saldo_awal ?? Carbon::now()->format('Y-m');
        return redirect()->route('coa.index', ['month' => $redirectMonth])
            ->with('success', 'COA berhasil diupdate!');
    }

    // Delete COA ketika tidak ada jurnal yang terkait
    public function destroy($id)
    {
        $coa = Coa::where('id_perusahaan', Auth::user()->id_perusahaan)->findOrFail($id);

        // Check if there are any related journal entries
        if ($coa->jurnalUmums()->exists()) {
            return redirect()->route('coa.index')->with('error', 'COA tidak bisa dihapus karena berhubungan dengan jurnal!');
        }

        $coa->delete();

        return redirect()->route('coa.index')->with('success', 'COA berhasil dihapus!');
    }
}