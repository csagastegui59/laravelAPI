<?php

namespace Database\Factories\Api;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Api\JobOpening;

class CandidateFactory extends Factory
{
    public function definition()
    {
        return [
            'first_name' => fake()->name(),
            'last_name' => fake()->name(),
            'job_opening_id' => JobOpening::factory(),
            'email' => fake()->unique()->safeEmail(),
            'password' => bcrypt('password'),
            'application_status' => fake()->randomElement(['human_resources', 'engineering', 'accepted'])
        ];
    }
}
