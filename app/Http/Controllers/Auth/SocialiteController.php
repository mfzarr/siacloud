<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use GuzzleHttp\Client;

class SocialiteController extends Controller
{
    public function redirect(){
        // Konfigurasi Guzzle untuk menonaktifkan verifikasi SSL
        $client = new Client(['verify' => false]);
        
        return Socialite::driver('google')
            ->setHttpClient($client)
            ->redirect();
    }

    public function callback(){
        try {
            // Konfigurasi Guzzle untuk menonaktifkan verifikasi SSL
            $client = new Client(['verify' => false]);
            
            $socialUser = Socialite::driver('google')
                ->setHttpClient($client)
                ->user();

            $registeredUser = User::where('google_id', $socialUser->id)->first();

            if (!$registeredUser) {
                // Periksa apakah email sudah terdaftar
                $existingUser = User::where('email', $socialUser->email)->first();
                
                if ($existingUser) {
                    // Update user yang sudah ada dengan google_id
                    $existingUser->google_id = $socialUser->id;
                    $existingUser->google_token = $socialUser->token;
                    $existingUser->google_refresh_token = $socialUser->refreshToken;
                    $existingUser->save();
                    
                    Auth::login($existingUser);
                    
                    // Redirect berdasarkan role
                    if (!$existingUser->role) {
                        return redirect('/role-selection');
                    }
                    return redirect('/dashboard');
                }
                
                // Buat user baru
                $user = User::create([
                    'name' => $socialUser->name,
                    'email' => $socialUser->email,
                    'password' => Hash::make('123123123'), // Generate a random password
                    'google_id' => $socialUser->id,
                    'google_token' => $socialUser->token,
                    'google_refresh_token' => $socialUser->refreshToken,
                    'role' => null, // Atau 'pending' jika kolom tidak boleh null
                ]);
             
                Auth::login($user);
             
                return redirect('/role-selection');
            }
            
            Auth::login($registeredUser);
             
            return redirect('/dashboard');
        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error('Google OAuth Error: ' . $e->getMessage());
            
            return redirect('/login')->with('error', 'Login dengan Google gagal: ' . $e->getMessage());
        }
    }
}