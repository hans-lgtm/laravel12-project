<?php

namespace Database\Seeders;

use App\Models\AccessCode;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AccessCodeSeeder extends Seeder
{
    public function run()
    {
        $codes = [];
        
        for ($i = 0; $i < 550; $i++) {
            $codes[] = [
                'code' => $this->generateUniqueCode(),
                'is_used' => false,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        // Insert dalam batch
        foreach (array_chunk($codes, 50) as $chunk) {
            AccessCode::insert($chunk);
        }
    }

    private function generateUniqueCode()
    {
        do {
            // Generate kode 6 karakter: kombinasi angka dan huruf
            $code = Str::upper(Str::random(6));
        } while (AccessCode::where('code', $code)->exists());

        return $code;
    }
}