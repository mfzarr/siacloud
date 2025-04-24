<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $loginField = $request->input('login');
        $password = $request->input('password');
        $remember = $request->has('remember');
    
        $fieldType = filter_var($loginField, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    
        $credentials = [
            $fieldType => $loginField,
            'password' => $password
        ];
    
        if (Auth::attempt($credentials, $remember)) {
            // Jika remember me dicentang, simpan login field ke cookie
            if ($remember) {
                Cookie::queue('remembered_login', $loginField, 43200); // 43200 menit = 30 hari
            } else {
                Cookie::queue(Cookie::forget('remembered_login'));
            }
            
            return redirect()->intended('/dashboard');
        }
    
        return redirect()->back()
            ->withInput($request->only('login'))
            ->withErrors(['login' => 'These credentials do not match our records.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }
}