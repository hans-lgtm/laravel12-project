<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use App\Models\Candidate;
use App\Models\AccessCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class ElectionController extends Controller
{
    // HAPUS CONSTRUCTOR INI
    // public function __construct()
    // {
    //     $this->middleware('access.code')->except(['results']);
    // }

    public function index()
    {
        // Manual check access code
        if (!Session::get('access_code_verified')) {
            return redirect()->route('access-code.verify');
        }

        $candidates = Candidate::all();
        
        // Cek apakah sudah memilih berdasarkan session kode akses
        $accessCodeId = Session::get('access_code_id');
        $hasVoted = Vote::where('access_code_id', $accessCodeId)->exists();
        
        return view('election.index', compact('candidates', 'hasVoted'));
    }

    public function showVisionMission($id)
    {
        // Manual check access code
        if (!Session::get('access_code_verified')) {
            return redirect()->route('access-code.verify');
        }

        $candidate = Candidate::findOrFail($id);
        return view('election.vision-mission', compact('candidate'));
    }

    public function vote(Request $request, $id)
{
    // Manual check access code
    if (!Session::get('access_code_verified')) {
        return redirect()->route('access-code.verify');
    }

    try {
        DB::beginTransaction();

        // Validasi candidate exists
        $candidate = Candidate::findOrFail($id);

        // Cek kode akses dari session
        $accessCodeId = Session::get('access_code_id');
        
        if (!$accessCodeId) {
            return redirect()->route('access-code.verify')
                ->with('error', 'Sesi kode akses tidak valid. Silakan login kembali.');
        }

        // Cek apakah sudah memilih dengan kode akses ini
        if (Vote::where('access_code_id', $accessCodeId)->exists()) {
            return redirect()->back()->with('error', 'Anda sudah melakukan pemilihan!');
        }

        // Simpan vote
        Vote::create([
            'candidate_id' => $id,
            'access_code_id' => $accessCodeId,
            'voter_identifier' => Session::get('access_code')
        ]);

        DB::commit();

        // Hapus session untuk auto logout
        Session::forget('access_code_verified');
        Session::forget('access_code_id');
        Session::forget('access_code');

        return redirect()->route('access-code.verify')
            ->with('success', 'Terima kasih! Suara Anda untuk Paslon ' . $candidate->number . ' telah tercatat. Anda telah logout otomatis.');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Vote error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan pilihan.');
    }
}

    public function results()
    {
        // Results page bisa diakses tanpa kode akses
        $candidates = Candidate::withCount('votes')->get();
        $totalVotes = Vote::count();
        
        return view('election.results', compact('candidates', 'totalVotes'));
    }
}