<?php

namespace Database\Seeders;

use App\Models\AccessCode;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ExportAccessCodesSeeder extends Seeder
{
    public function run()
    {
        $codes = AccessCode::all();
        $content = "DAFTAR KODE AKSES PEMILIHAN OSIS\n";
        $content .= "================================\n\n";
        
        $unusedCount = 0;
        $usedCount = 0;
        
        foreach ($codes as $code) {
            $status = $code->is_used ? 'SUDAH DIGUNAKAN' : 'BELUM DIGUNAKAN';
            $usedInfo = $code->is_used ? " (Digunakan pada: {$code->used_at})" : "";
            
            $content .= "Kode: {$code->code} - Status: {$status}{$usedInfo}\n";
            
            if ($code->is_used) {
                $usedCount++;
            } else {
                $unusedCount++;
            }
        }
        
        $content .= "\n================================\n";
        $content .= "Total Kode: " . $codes->count() . "\n";
        $content .= "Belum Digunakan: {$unusedCount}\n";
        $content .= "Sudah Digunakan: {$usedCount}\n";
        
        // Simpan ke file di storage
        Storage::put('access_codes.txt', $content);
        
        // Juga simpan ke public folder untuk mudah diakses
        file_put_contents(public_path('kode_akses.txt'), $content);
        
        $this->command->info('Kode akses telah diexport ke:');
        $this->command->info('- storage/app/access_codes.txt');
        $this->command->info('- public/kode_akses.txt');
        $this->command->info("Total: {$codes->count()} kode, {$unusedCount} belum digunakan, {$usedCount} sudah digunakan");
    }
}