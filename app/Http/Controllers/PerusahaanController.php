<?php

namespace App\Http\Controllers;

use App\Models\Perusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth; // Import Auth to get the logged-in user
use App\Models\User; // Import User model

class PerusahaanController extends Controller
{
    /**
     * Show the create perusahaan form.
     */
    public function showCreateForm()
    {
        return view('auth.perusahaan'); // View for the form
    }

    /**
     * Handle the form submission and create a perusahaan.
     */
    public function createPerusahaan(Request $request)
    {
        // Validate the input fields
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'jenis_perusahaan' => 'required|string|max:255',
        ]);

        // Get the logged-in user
        $user = Auth::user();

        // Find existing perusahaan for the user if exists
        $perusahaan = $user->perusahaan; // Assuming user relation with perusahaan

        // Generate a unique 9-character alphanumeric code for perusahaan
        $kode_perusahaan = $this->generateUniqueKodePerusahaan($perusahaan);

        // If perusahaan doesn't exist, create a new one
        if (!$perusahaan) {
            $perusahaan = Perusahaan::create([
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'jenis_perusahaan' => $request->jenis_perusahaan,
                'kode_perusahaan' => $kode_perusahaan,
                'kode_perusahaan_generated_at' => now(), // Set current time for code generation
            ]);

            // Assign the newly created perusahaan to the logged-in user
            $user->id_perusahaan = $perusahaan->id_perusahaan;
            $user->save();
        } else {
            // If perusahaan exists, just update the timestamp and kode
            $perusahaan->kode_perusahaan = $kode_perusahaan;
            $perusahaan->kode_perusahaan_generated_at = now(); // Update the generation time
            $perusahaan->save();
        }

        // Redirect to the dashboard with success message
        return redirect()->route('dashboard')->with('success', 'Perusahaan created and assigned successfully!');
    }

    /**
     * Generate a unique 9-character alphanumeric code for kode_perusahaan
     * and ensure it's regenerated if it's older than 5 minutes.
     */
    private function generateUniqueKodePerusahaan(Perusahaan $perusahaan = null)
    {
        // If the perusahaan already exists and kode is still valid (within 5 minutes)
        if ($perusahaan && $perusahaan->kode_perusahaan_generated_at) {
            $generatedAt = $perusahaan->kode_perusahaan_generated_at;
            $currentTime = now();

            // Check if the code is older than 5 minutes
            if ($currentTime->diffInMinutes($generatedAt) < 5) {
                return $perusahaan->kode_perusahaan; // Return the existing code if still valid
            }
        }

        // Generate a new unique code if it's not valid or doesn't exist
        do {
            $kode_perusahaan = Str::random(9);
        } while (Perusahaan::where('kode_perusahaan', $kode_perusahaan)->exists());

        return $kode_perusahaan;
    }
}
