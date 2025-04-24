<?php

namespace App\Http\Controllers\Masterdata;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCoaKelompokRequest;
use App\Http\Requests\UpdateCoaKelompokRequest;
use App\Models\Masterdata\CoaKelompok;
use Illuminate\Support\Facades\Auth;

class CoaKelompokController extends Controller
{
    /**
     * Display a listing of the COA Kelompok.
     */
    public function index()
    {
        // Ambil id_perusahaan dari user yang sedang login
        $idPerusahaan = Auth::user()->id_perusahaan;

        // Ambil semua data COA Kelompok berdasarkan id_perusahaan
        $coaKelompoks = CoaKelompok::where('id_perusahaan', $idPerusahaan)->get();

        return view('masterdata.coa-kelompok.index', [
            'coaKelompoks' => $coaKelompoks,
            'table' => 'coa-kelompok'
        ]);
    }

    /**
     * Store a newly created COA Kelompok in the database.
     */
    public function store(StoreCoaKelompokRequest $request)
    {
        // Validasi data menggunakan StoreCoaKelompokRequest
        $data = $request->validated();

        // Tambahkan id_perusahaan dari user yang sedang login
        $data['id_perusahaan'] = Auth::user()->id_perusahaan;

        CoaKelompok::create($data);

        return response()->json([
            'message' => 'COA Kelompok successfully created!'
        ]);
    }

    /**
     * Show the form for editing the specified COA Kelompok record.
     */
    public function edit($id)
    {
        // Ambil id_perusahaan dari user yang sedang login
        $idPerusahaan = Auth::user()->id_perusahaan;

        // Ambil data COA Kelompok berdasarkan id_coa_kelompok dan id_perusahaan
        $coaKelompok = CoaKelompok::where('id_coa_kelompok', $id)
            ->where('id_perusahaan', $idPerusahaan)
            ->first();

        return view('masterdata.coa-kelompok.edit', [
            'coaKelompok' => $coaKelompok
        ]);
    }

    /**
     * Update the specified COA Kelompok in the database.
     */
    public function update(UpdateCoaKelompokRequest $request, $id)
    {
        // Validasi data menggunakan UpdateCoaKelompokRequest
        $data = $request->validated();

        // Ambil id_perusahaan dari user yang sedang login
        $idPerusahaan = Auth::user()->id_perusahaan;

        // Update data COA Kelompok berdasarkan id_coa_kelompok dan id_perusahaan
        CoaKelompok::where('id_coa_kelompok', $id)
            ->where('id_perusahaan', $idPerusahaan)
            ->update($data);

        return response()->json([
            'message' => 'COA Kelompok successfully updated!'
        ]);
    }

    /**
     * Remove the specified COA Kelompok from the database.
     */
    public function destroy($table, $id)
    {
        // Ambil id_perusahaan dari user yang sedang login
        $idPerusahaan = Auth::user()->id_perusahaan;

        // Hapus data COA Kelompok berdasarkan id_coa_kelompok dan id_perusahaan
        if ($table == 'coaKelompoks') {
            CoaKelompok::where('id_coa_kelompok', $id)
                ->where('id_perusahaan', $idPerusahaan)
                ->delete();
        }

        return response()->json([
            'message' => 'Item deleted successfully'
        ]);
    }
}
