<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Perusahaan;
use Illuminate\Http\Request;

class MenampilkanPerusahaanController extends Controller
{
    /**
     * Display the perusahaan that belongs to the logged-in user.
     */
    public function index()
    {
        // Get the logged-in user
        $user = Auth::user();

        // If the user is associated with a perusahaan, fetch it
        if ($user->id_perusahaan) {
            // Fetch the perusahaan that belongs to the user
            $perusahaan = Perusahaan::where('id_perusahaan', $user->id_perusahaan)->with('owner')->first();
        } else {
            // No perusahaan assigned to the user
            $perusahaan = null;
        }

        // Pass the perusahaan data to the view
        return view('perusahaan.index', compact('perusahaan'));
    }
}

