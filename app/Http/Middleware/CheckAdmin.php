<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek jika admin sudah login
        if (Session::get('admin_verified')) {
            return $next($request);
        }

        // Redirect ke halaman input kode akses
        return redirect()->route('access-code.verify')
            ->with('error', 'Anda harus login sebagai admin untuk mengakses halaman ini.');
    }
}