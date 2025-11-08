<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\AccessCode;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index()
    {
        $candidates = Candidate::all();
        return view('admin.index', compact('candidates'));
    }

    public function create()
    {
        return view('admin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'chairman_name' => 'required|string|max:255',
            'vice_chairman_name' => 'required|string|max:255',
            'number' => 'required|string|unique:candidates,number',
            'vision' => 'required|string',
            'mission' => 'required|string',
            'chairman_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'vice_chairman_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only([
            'chairman_name', 
            'vice_chairman_name', 
            'number', 
            'vision', 
            'mission'
        ]);

        // Upload foto ketua
        if ($request->hasFile('chairman_photo')) {
            $data['chairman_photo'] = $request->file('chairman_photo')->store('candidates', 'public');
        }

        // Upload foto wakil ketua
        if ($request->hasFile('vice_chairman_photo')) {
            $data['vice_chairman_photo'] = $request->file('vice_chairman_photo')->store('candidates', 'public');
        }

        Candidate::create($data);

        return redirect()->route('admin.index')->with('success', 'Paslon berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $candidate = Candidate::findOrFail($id);
        return view('admin.edit', compact('candidate'));
    }

    public function update(Request $request, $id)
    {
        $candidate = Candidate::findOrFail($id);

        $request->validate([
            'chairman_name' => 'required|string|max:255',
            'vice_chairman_name' => 'required|string|max:255',
            'number' => 'required|string|unique:candidates,number,' . $id,
            'vision' => 'required|string',
            'mission' => 'required|string',
            'chairman_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'vice_chairman_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only([
            'chairman_name', 
            'vice_chairman_name', 
            'number', 
            'vision', 
            'mission'
        ]);

        // Update foto ketua
        if ($request->hasFile('chairman_photo')) {
            // Hapus foto lama jika ada
            if ($candidate->chairman_photo) {
                Storage::disk('public')->delete($candidate->chairman_photo);
            }
            $data['chairman_photo'] = $request->file('chairman_photo')->store('candidates', 'public');
        }

        // Update foto wakil ketua
        if ($request->hasFile('vice_chairman_photo')) {
            // Hapus foto lama jika ada
            if ($candidate->vice_chairman_photo) {
                Storage::disk('public')->delete($candidate->vice_chairman_photo);
            }
            $data['vice_chairman_photo'] = $request->file('vice_chairman_photo')->store('candidates', 'public');
        }

        $candidate->update($data);

        return redirect()->route('admin.index')->with('success', 'Paslon berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $candidate = Candidate::findOrFail($id);
        
        // Hapus foto jika ada
        if ($candidate->chairman_photo) {
            Storage::disk('public')->delete($candidate->chairman_photo);
        }
        if ($candidate->vice_chairman_photo) {
            Storage::disk('public')->delete($candidate->vice_chairman_photo);
        }
        
        $candidate->delete();

        return redirect()->route('admin.index')->with('success', 'Paslon berhasil dihapus!');
    }

    // METHOD BARU UNTUK KELOLA KODE AKSES
    public function accessCodes()
    {
        $unusedCodes = AccessCode::where('is_used', false)->get();
        $usedCodes = AccessCode::where('is_used', true)->get();
        $totalCodes = AccessCode::count();
        
        return view('admin.access-codes', compact('unusedCodes', 'usedCodes', 'totalCodes'));
    }

    public function exportAccessCodes()
    {
        $codes = AccessCode::all();
        $content = "Kode Akses Pemilihan OSIS\n\n";
        
        foreach ($codes as $code) {
            $status = $code->is_used ? 'SUDAH DIGUNAKAN' : 'BELUM DIGUNAKAN';
            $content .= "{$code->code} - {$status}\n";
        }
        
        return response($content)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="kode_akses.txt"');
    }

    // METHOD UNTUK STATISTIK
    public function statistics()
    {
        $totalVoters = AccessCode::count();
        $votedCount = Vote::count();
        $unusedCodes = AccessCode::where('is_used', false)->count();
        $usedCodes = AccessCode::where('is_used', true)->count();
        
        $candidates = Candidate::withCount('votes')->get();
        
        // Hitung persentase
        $votingPercentage = $totalVoters > 0 ? round(($votedCount / $totalVoters) * 100, 2) : 0;
        
        // Data untuk chart
        $candidateNames = $candidates->pluck('number');
        $candidateVotes = $candidates->pluck('votes_count');
        
        return view('admin.statistics', compact(
            'totalVoters',
            'votedCount',
            'unusedCodes',
            'usedCodes',
            'votingPercentage',
            'candidates',
            'candidateNames',
            'candidateVotes'
        ));
    }

    // METHOD TAMBAHAN: GENERATE KODE AKSES BARU
    public function generateAccessCodes(Request $request)
    {
        $request->validate([
            'count' => 'required|integer|min:1|max:100'
        ]);

        $count = $request->count;
        $generated = 0;

        for ($i = 0; $i < $count; $i++) {
            do {
                $code = strtoupper(\Illuminate\Support\Str::random(6));
            } while (AccessCode::where('code', $code)->exists());

            AccessCode::create(['code' => $code]);
            $generated++;
        }

        return redirect()->route('admin.access-codes')
            ->with('success', "Berhasil generate {$generated} kode akses baru!");
    }

    // METHOD TAMBAHAN: HAPUS KODE AKSES
    public function deleteAccessCode($id)
    {
        $accessCode = AccessCode::findOrFail($id);
        
        // Cek jika kode sudah digunakan
        if ($accessCode->is_used) {
            return redirect()->route('admin.access-codes')
                ->with('error', 'Tidak dapat menghapus kode yang sudah digunakan!');
        }

        $accessCode->delete();

        return redirect()->route('admin.access-codes')
            ->with('success', 'Kode akses berhasil dihapus!');
    }

    // METHOD UNTUK HAPUS SEMUA KODE AKSES
public function deleteAllAccessCodes(Request $request)
{
    $type = $request->type; // all, used, unused
    
    try {
        switch ($type) {
            case 'used':
                $count = AccessCode::where('is_used', true)->count();
                AccessCode::where('is_used', true)->delete();
                $message = "Berhasil menghapus {$count} kode yang sudah digunakan!";
                break;
                
            case 'unused':
                $count = AccessCode::where('is_used', false)->count();
                AccessCode::where('is_used', false)->delete();
                $message = "Berhasil menghapus {$count} kode yang belum digunakan!";
                break;
                
            case 'all':
            default:
                $count = AccessCode::count();
                AccessCode::truncate(); // Hapus semua data termasuk reset auto increment
                $message = "Berhasil menghapus semua {$count} kode akses!";
                break;
        }
        
        return redirect()->route('admin.access-codes')
            ->with('success', $message);
            
    } catch (\Exception $e) {
        return redirect()->route('admin.access-codes')
            ->with('error', 'Terjadi kesalahan saat menghapus kode: ' . $e->getMessage());
    }
}

// METHOD UNTUK RESET SEMUA KODE (Hapus semua dan generate baru)
public function resetAllAccessCodes(Request $request)
{
    $request->validate([
        'count' => 'required|integer|min:1|max:500'
    ]);

    try {
        // Hapus semua kode lama
        $oldCount = AccessCode::count();
        AccessCode::truncate();
        
        // Generate kode baru
        $count = $request->count;
        $generated = 0;
        $codes = [];

        for ($i = 0; $i < $count; $i++) {
            do {
                $code = strtoupper(\Illuminate\Support\Str::random(6));
            } while (in_array($code, $codes)); // Cek duplikasi dalam batch

            $codes[] = $code;
            $generated++;
        }

        // Insert dalam batch untuk performa
        $batchData = [];
        foreach ($codes as $code) {
            $batchData[] = [
                'code' => $code,
                'is_used' => false,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        AccessCode::insert($batchData);

        return redirect()->route('admin.access-codes')
            ->with('success', "Berhasil reset! Menghapus {$oldCount} kode lama dan generate {$generated} kode baru!");

    } catch (\Exception $e) {
        return redirect()->route('admin.access-codes')
            ->with('error', 'Terjadi kesalahan saat reset kode: ' . $e->getMessage());
    }
}
}