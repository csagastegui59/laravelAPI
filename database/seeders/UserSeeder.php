<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Api\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::factory()->create();
        User::factory()->create(['role' => 'company_admin']);
        User::factory()->create(['role' => 'recruiter']);
    }
}