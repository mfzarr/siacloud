<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleSelectionController extends Controller
{
    /**
     * Display the role selection view.
     */
    public function showRoleSelectionForm()
    {
        return view('auth.role-selection');
    }

    /**
     * Handle the role selection.
     */
    public function handleRoleSelection(Request $request)
    {
        $request->validate([
            'role' => ['required', 'string', 'in:owner,pegawai'],
        ]);

        // Update user role
        $user = Auth::user();
        $user->role = $request->role;
        $user->save();

        // Redirect based on role
        if ($user->role === 'owner') {
            // Redirect owners to the Perusahaan registration form
            return redirect()->route('registrasi-perusahaan');
        } else {
            // Redirect pegawai to the input kode perusahaan form
            return redirect()->route('input-kode-perusahaan');
        }
    }
}