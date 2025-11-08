<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'chairman_name',
        'vice_chairman_name', 
        'number',
        'vision',
        'mission',
        'chairman_photo',
        'vice_chairman_photo'
    ];

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
    
    // Accessor untuk votes_count jika diperlukan
    public function getVotesCountAttribute()
    {
        return $this->votes()->count();
    }
}