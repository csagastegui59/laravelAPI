<?php

namespace Database\Factories\Api;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Api\Company;

class UserFactory extends Factory
{
    public function definition()
    {
        return [
            'first_name' => fake()->name(),
            'last_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'company_id' => Company::factory(),
            'role' => 'api_admin',
            'password' => bcrypt('password')
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
