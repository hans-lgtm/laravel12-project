<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CheckAccessCode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek jika kode akses sudah valid di session
        if (Session::get('access_code_verified')) {
            return $next($request);
        }

        // Redirect ke halaman input kode akses
        return redirect()->route('access-code.verify');
    }
}