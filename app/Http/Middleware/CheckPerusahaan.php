<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPerusahaan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is logged in
        if (Auth::check()) {
            // Check if the user has already an assigned id_perusahaan
            $user = Auth::user();
            
            // If user already has a perusahaan, redirect to dashboard (or other route)
            if ($user->id_perusahaan) {
                return redirect()->route('dashboard')
                                 ->with('message', 'You already have a company assigned.');
            }
        }

        // Proceed to input Kode Perusahaan form if no company is assigned
        return $next($request);
    }
}
