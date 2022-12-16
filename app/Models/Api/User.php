<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory;
    public $timestamps = false;

    protected $fillable = [
      'first_name',
      'last_name',
      'email',
      'password',
      'role',
      'company_id'
    ];

    protected $hidden = [
      'password',
      'remember_token',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}