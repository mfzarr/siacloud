<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the input including password confirmation and role selection
        $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
            ],
            'role' => ['required', 'string', 'in:owner,pegawai'],
        ]);

        // Create the new user and hash the password
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role, // Save role correctly
        ]);

        // Fire the registered event and log in the user
        event(new Registered($user));
        Auth::login($user);

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
