<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'is_used',
        'used_at',
        'used_by_ip'
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'used_at' => 'datetime'
    ];

    // Scope untuk kode yang belum digunakan
    public function scopeUnused($query)
    {
        return $query->where('is_used', false);
    }

    // Scope untuk kode yang sudah digunakan
    public function scopeUsed($query)
    {
        return $query->where('is_used', true);
    }

    // Mark kode sebagai digunakan
    public function markAsUsed($ip = null)
    {
        $this->update([
            'is_used' => true,
            'used_at' => now(),
            'used_by_ip' => $ip
        ]);
    }
}