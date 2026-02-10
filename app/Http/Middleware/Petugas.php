<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Petugas
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        if (Auth::user()->role !== 'petugas') {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Akses ditolak. Anda tidak memiliki akses ke halaman petugas.');
        }

        return $next($request);
    }
}