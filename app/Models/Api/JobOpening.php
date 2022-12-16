<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobOpening extends Model
{
    use HasFactory;
    public $timestamps = false;
    
    protected $guarded = [
        'id'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }

    public function applicants()
    {
        return $this->hasMany(Candidate::class);
    }
}
