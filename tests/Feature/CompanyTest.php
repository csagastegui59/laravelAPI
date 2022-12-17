<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use \App\Models\Api\{ User, Company };
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class CompanyTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_it_should_allow_api_admin_user_to_access_show_all_companies_endpoint()
    {
        $user = Sanctum::actingAs(
            User::factory()->create([
                'role' => 'api_admin'
            ]), ['api:admin']
        );

        $companies = Company::factory(10)->create();

        $response = $this->get('/api/companies/');

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'All Companies',
                'pagination' => [
                    'total_items' => 11
                ]
            ]);
    }

    public function test_it_should_not_allow_another_user_to_access_show_all_companies_endpoint()
    {
        $companies = Company::factory(10)->create();
        
        $response = $this->get('/api/companies/');

        $response->assertStatus(500);
    }

    public function test_it_should_allow_api_admin_to_create_a_company()
    {
        $user = Sanctum::actingAs(
            User::factory()->create([
                'role' => 'api_admin'
            ]), ['api:admin']
        );

        $data = [
            'name' => fake()->company()
        ];

        $response = $this->post('/api/companies/', $data);

        $response->assertStatus(201)
            ->assertJson([
                'status' => true,
                'message' => 'Company created successfuly',
                'company' => [
                    'name' => $data['name']
                ]
            ]);
    }

    public function test_it_should_not_allow_not_api_admin_user_to_create_a_company()
    {
        $data = [
            'name' => fake()->company()
        ];

        $response = $this->post('/api/companies/', $data);

        $response->assertStatus(500);
    }

    public function test_it_should_allow_api_admin_to_update_a_company()
    {
        $user = Sanctum::actingAs(
            User::factory()->create([
                'role' => 'api_admin'
            ]), ['api:admin']
        );

        $data = [
            'name' => fake()->company()
        ];

        $company = $user->company;

        $response = $this->put('/api/companies/'.$company->id, $data);

        $response->assertStatus(201)
            ->assertJson([
                'status' => true,
                'message' => 'Company updated',
                'company' => [
                    'name' => $data['name']
                ]
            ]);
    }

    public function test_it_should_not_allow_not_api_admin_user_to_update_a_company()
    {
        $user = User::factory()->create();

        $data = [
            'name' => fake()->company()
        ];

        $company = $user->company;

        $response = $this->put('/api/companies/'.$company->id, $data);

        $response->assertStatus(500);
    }

    public function test_it_should_allow_api_admin_to_delete_a_company()
    {
        $user = Sanctum::actingAs(
            User::factory()->create([
                'role' => 'api_admin'
            ]), ['api:admin']
        );

        $company = Company::factory()->create();

        $response = $this->delete('/api/companies/'.$company->id);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'company deleted',
            ]);
    }

    public function test_it_should_not_allow_not_api_admin_to_delete_a_company()
    {
        $company = Company::factory()->create();

        $response = $this->delete('/api/companies/'.$company->id);

        $response->assertStatus(500);
    }

    public function test_it_should_allow_api_admin_to_show_a_company()
    {
        $user = Sanctum::actingAs(
            User::factory()->create([
                'role' => 'api_admin'
            ]), ['api:admin']
        );

        $company = $user->company;

        $response = $this->get('/api/companies/'.$company->id);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => $company->name . ' Company',
                'company' => [
                    'id' => $company->id,
                    'name' => $company->name,
                ]
            ]);
    }

    public function test_it_should_not_allow_not_api_admin_to_show_a_company()
    {
        $company = Company::factory()->create();

        $response = $this->get('/api/companies/'.$company->id);

        $response->assertStatus(500);
    }
}
