<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Masterdata\Karyawan;
use App\Models\Transaksi\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class presensiController extends Controller
{
    public function index()
    {
        // Get today's date
        $today = now()->format('Y-m-d');

        // Get the attendance summary for the company
        $attendanceSummary = Presensi::whereHas('karyawan', function ($query) {
            $query->where('id_perusahaan', auth()->user()->perusahaan->id_perusahaan);
        })
            ->get()
            ->groupBy('tanggal_presensi')  // Group by date
            ->map(function ($dateRecords) {
                return [
                    'hadir' => $dateRecords->where('status', 'hadir')->count(),
                    'sakit' => $dateRecords->where('status', 'sakit')->count(),
                    'izin' => $dateRecords->where('status', 'izin')->count(),
                    'alpha' => $dateRecords->where('status', 'alpha')->count(),
                    'terlambat' => $dateRecords->where('status', 'terlambat')->count(),
                ];
            });

        return view('transaksi.presensi.index', compact('attendanceSummary', 'today'));
    }

    // Display attendance form for today for the logged-in user's company
    public function create()
    {
        // Get the logged-in user's company ID
        $perusahaanId = auth()->user()->perusahaan->id_perusahaan;
    
        // Get today's date
        $today = now()->format('Y-m-d');
    
        // Get the list of karyawans for this company who haven't been recorded for today
        $karyawans = Karyawan::where('id_perusahaan', $perusahaanId)
            ->where('status', 'Aktif')
            ->whereDoesntHave('presensi', function ($query) use ($today) {
            $query->where('tanggal_presensi', $today);
            })
            ->get();
    
        return view('transaksi.presensi.create', compact('karyawans', 'today'));
    }

    public function update(Request $request, $date)
    {
        $request->validate([
            'status' => 'required|array',
            'status.*' => 'in:hadir,izin,sakit,alpha,terlambat',
            'jam_masuk' => 'nullable|array',
            'jam_masuk.*' => 'nullable|date_format:H:i',
            'jam_keluar' => 'nullable|array',
            'jam_keluar.*' => 'nullable|date_format:H:i',
        ]);
    
        foreach ($request->status as $karyawanId => $status) {
            $attendance = Presensi::where('id_karyawan', $karyawanId)
                ->where('tanggal_presensi', $date)
                ->first();
    
            if ($attendance) {
                $updateData = ['status' => $status];
    
                if (in_array($status, ['izin', 'sakit', 'alpha'])) {
                    $updateData['jam_masuk'] = null;
                    $updateData['jam_keluar'] = null;
                } else {
                    $updateData['jam_masuk'] = $request->jam_masuk[$karyawanId] ?? $attendance->jam_masuk;
                    $updateData['jam_keluar'] = $request->jam_keluar[$karyawanId] ?? $attendance->jam_keluar;
                }
    
                $attendance->update($updateData);
            }
        }
    
        return redirect()->route('presensi.index')->with('success', 'Attendance records updated successfully.');
    }

    public function edit($date)
    {
        // Get the attendance records for the specified date
        $attendance = Presensi::where('tanggal_presensi', $date)
            ->where('id_perusahaan', auth()->user()->perusahaan->id_perusahaan)
            ->get();

        return view('transaksi.presensi.edit', compact('attendance', 'date'));
    }

    public function destroy($date)
    {
        // Delete the attendance records for the specified date
        Presensi::where('tanggal_presensi', $date)
            ->where('id_perusahaan', auth()->user()->perusahaan->id_perusahaan)
            ->delete();

        return redirect()->route('presensi.index')->with('success', 'Attendance records deleted successfully.');
    }

    // Store the attendance record for each employee
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_presensi' => 'required|date',
            'status' => 'required|array',
            'status.*' => 'in:hadir,izin,sakit,alpha,terlambat',
            'jam_masuk' => 'nullable|array',
            'jam_masuk.*' => 'nullable|date_format:H:i',
            'jam_keluar' => 'nullable|array',
            'jam_keluar.*' => 'nullable|date_format:H:i',
        ]);
    
        foreach ($request->status as $karyawanId => $status) {
            $data = [
                'id_karyawan' => $karyawanId,
                'tanggal_presensi' => $request->tanggal_presensi,
                'status' => $status,
                'id_perusahaan' => auth()->user()->perusahaan->id_perusahaan,
            ];
    
            if (in_array($status, ['izin', 'sakit', 'alpha'])) {
                $data['jam_masuk'] = null;
                $data['jam_keluar'] = null;
            } else {
                $data['jam_masuk'] = $request->jam_masuk[$karyawanId] ?? null;
                $data['jam_keluar'] = $request->jam_keluar[$karyawanId] ?? null;
            }
    
            Presensi::updateOrCreate(
                ['id_karyawan' => $karyawanId, 'tanggal_presensi' => $request->tanggal_presensi],
                $data
            );
        }
    
        return redirect()->route('presensi.index')->with('success', 'Attendance records created successfully.');
    }

    // Show detailed attendance records for a specific date
    public function show($date)
    {
        $attendance = Presensi::where('tanggal_presensi', $date)
            ->where('id_perusahaan', auth()->user()->perusahaan->id_perusahaan)
            ->get();

        return view('transaksi.presensi.show', compact('attendance', 'date'));
    }
    public function createExitTime(Request $request, $date, $id)
    {
        $presensi = Presensi::findOrFail($id);
    
        if ($presensi->jam_keluar) {
            return redirect()->back()->with('error', 'Exit time is already set for this record.');
        }
    
        $presensi->jam_keluar = now()->setTimezone('Asia/Jakarta')->format('H:i:s');
        $presensi->save();
    
        return redirect()->route('presensi.show', $date)->with('success', 'Exit time has been recorded successfully.');
    }

}
// Create the 'jam_keluar' for each employee when the button is clicked
