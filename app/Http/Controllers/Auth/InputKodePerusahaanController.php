<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Perusahaan;
use Illuminate\Support\Facades\Auth;

class InputKodePerusahaanController extends Controller
{
    /**
     * Show the input kode perusahaan form.
     */
    public function showInputForm()
    {
        return view('auth.input-kode-perusahaan');
    }

    /**
     * Handle the kode perusahaan input form submission.
     */
    public function handleInputKode(Request $request)
    {
        // Validate the input kode_perusahaan
        $request->validate([
            'kode_perusahaan' => 'required|string|exists:perusahaan,kode_perusahaan', // Ensure the code exists
        ]);

        // Find the perusahaan using the input kode
        $perusahaan = Perusahaan::where('kode_perusahaan', $request->kode_perusahaan)->first();

        // Assign the perusahaan to the logged-in user
        $user = Auth::user();
        $user->id_perusahaan = $perusahaan->id_perusahaan;
        $user->save();

        // Redirect to the dashboard with success message
        return redirect()->route('dashboard')->with('success', 'Perusahaan assigned successfully!');
    }
}

