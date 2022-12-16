<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'job_opening_id',
        'email',
        'first_name',
        'last_name',
        'application_status',
        'password'
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];

    
    public function jobOpening()
    {
        return $this->belongsTo(JobOpening::class);
    }
}
