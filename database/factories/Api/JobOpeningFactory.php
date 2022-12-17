<?php

namespace Database\Factories\Api;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Api\{ JobOpening, Company };

class JobOpeningFactory extends Factory
{

    public function definition()
    {
        return [
            'company_id' => Company::factory(),
            'title' => fake()->jobTitle(),
            'description' => fake()->realText($maxNbChars = 200, $indexSize = 2),
            'is_published' => fake()->numberBetween(0, 1)
        ];
    }
}
