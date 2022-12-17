<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use App\Models\Api\{ User, Company };

class AuthenticationTest extends TestCase
{
    public function test_it_should_create_api_admin_when_giving_right_key()
    {
        $company = Company::factory()->create();

        $data = [
            'company_id' => $company->id,
            'email' => 'apiadmin@mail.com',
            'first_name' => 'Christian',
            'last_name' => 'Sagastegui',
            'role' => 'api_admin',
            'password' => bcrypt('password'),
            'secret_key' => '123456'
        ];

        $response = $this->post('/api/register', $data);

        $response->assertStatus(201)
        ->assertJson([
          'status' => true,
          'message' => 'User Created Successfully',
        ]);
    }

    public function test_it_should_throw_an_eror_when_giving_invalid_key()
    {
        $company = Company::factory()->create();

        $data = [
            'company_id' => $company->id,
            'email' => 'apiadmin1112@mail.com',
            'first_name' => 'Christian',
            'last_name' => 'Sagastegui',
            'role' => 'api_admin',
            'password' => bcrypt('password'),
            'secret_key' => '1234567'
        ];

        $response = $this->post('/api/register', $data);

        $response->assertStatus(422)
            ->assertJsonPath('errors.secret_key.0', 'Invalid secret key');
    }

    public function test_it_should_login_a_valid_user()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password')
        ]);

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'User Logged In Successfully',
            ])->assertJsonPath('user.id', $user->id);
    }

    public function test_it_should_throw_an_error_when_login_with_unvalid_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password')
        ]);

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password123'
        ]);

        $response->assertStatus(401);
    }

    public function test_it_should_logout_a_user()
    {
        $user = Sanctum::actingAs(
            User::factory()->create(), ['company:admin']
        );

        $response = $this->post('/api/logout');

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'logged out',
            ]);

    }

    public function test_it_should_not_allow_unauthenticated_users_to_access_logout_endpoint()
    {
        $response = $this->post('/api/logout');

        $response->assertStatus(500);
    }
}
