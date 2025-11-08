<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'access_code_id',
        'voter_identifier'
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function accessCode()
    {
        return $this->belongsTo(AccessCode::class);
    }
}