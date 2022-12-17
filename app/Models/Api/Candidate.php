<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    protected $guarded = [
        'id'
    ];
    
    public function jobOpening()
    {
        return $this->belongsTo(JobOpening::class);
    }
}
