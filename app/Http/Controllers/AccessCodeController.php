<?php

namespace App\Http\Controllers;

use App\Models\AccessCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AccessCodeController extends Controller
{
    public function showVerifyForm()
    {
        // Jika sudah verified, redirect ke home
        if (Session::get('access_code_verified')) {
            return redirect()->route('election.index');
        }

        return view('access-code.verify');
    }

    public function verifyCode(Request $request)
{
    $request->validate([
        'access_code' => 'required|string|size:6|alpha_num'
    ]);

    $code = strtoupper($request->access_code);

    // Kode khusus untuk admin
    $adminCodes = ['ADMIN0', 'ADMIN2', 'ADMIN3']; // Ganti dengan kode admin yang diinginkan

    if (in_array($code, $adminCodes)) {
        // Set session sebagai admin
        Session::put('admin_verified', true);
        Session::put('admin_code', $code);

        return redirect()->route('admin.index')
            ->with('success', 'Login admin berhasil! Selamat datang.');
    }

    // Cari kode yang belum digunakan untuk pemilih biasa
    $accessCode = AccessCode::where('code', $code)
        ->where('is_used', false)
        ->first();

    if (!$accessCode) {
        return redirect()->back()->withErrors([
            'access_code' => 'Kode akses tidak valid atau sudah digunakan.'
        ])->withInput();
    }

    // Mark kode sebagai digunakan
    $accessCode->update([
        'is_used' => true,
        'used_at' => now(),
        'used_by_ip' => $request->ip()
    ]);

    // Set session sebagai verified pemilih
    Session::put('access_code_verified', true);
    Session::put('access_code_id', $accessCode->id);
    Session::put('access_code', $code);

    return redirect()->route('election.index')
        ->with('success', 'Kode akses berhasil diverifikasi! Silakan pilih paslon.');
}

    public function logout(Request $request)
{
    // Hapus semua session
    Session::flush();

    return redirect()->route('access-code.verify')
        ->with('info', 'Anda telah logout dari sistem pemilihan.');
}
}