<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use \App\Models\Api\{ User, Company };
use Laravel\Sanctum\Sanctum;

class UserTest extends TestCase
{
    public function test_it_should_show_all_users_that_are_related_to_loged_user_company()
    {
        $relatedCompany = Company::factory()->create();

        User::factory(5)->create([
            'company_id' => $relatedCompany->id
        ]);

        $user = Sanctum::actingAs(
            User::factory()->create([
                'company_id' => $relatedCompany->id
            ]), ['api:admin']
        );

        User::factory(10)->create();

        $response = $this->get('/api/companies/'.$relatedCompany->id.'/users');

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => $relatedCompany->name.' users',
            ])
            ->assertJsonPath('pagination.total_items', 6)
            ->assertJsonPath('users.0.company_id', $relatedCompany->id)
            ->assertJsonPath('users.1.company_id', $relatedCompany->id)
            ->assertJsonPath('users.2.company_id', $relatedCompany->id)
            ->assertJsonPath('users.3.company_id', $relatedCompany->id)
            ->assertJsonPath('users.4.company_id', $relatedCompany->id)
            ->assertJsonPath('users.5.company_id', $relatedCompany->id);
    }

    public function test_it_should_throw_an_error_if_unauthenticated_user_try_to_access_all_users_company_endpoint()
    {
        $relatedCompany = Company::factory()->create();

        $response = $this->get('/api/companies/'.$relatedCompany->id.'/users');

        $response->assertStatus(500);
    }

    public function test_it_should_allow_authenticated_user_to_create_a_user_for_its_company()
    {
        $user = Sanctum::actingAs(
            User::factory()->create([
                'role' => 'company_admin'
            ]), ['company:admin']
        );

        $data = [
            'first_name' => 'Christian',
            'last_name' => 'Sagastegui',
            'email' => 'csagastegui@mail.com',
            'password' => bcrypt('password'),
            'role' => 'recruiter',
            'company_id' => $user->company_id,
        ];

        $response = $this->post('/api/companies/'.$user->company_id.'/users', $data);

        $response->assertStatus(201)
            ->assertJson([
                'status' => true,
                'message' => 'User Created Successfully',
            ])
            ->assertJsonPath('user.id', $response['user']['id'])
            ->assertJsonPath('user.company_id', $user->company_id);
    }

    public function test_it_should_throw_an_error_if_unauthenticated_user_try_to_access_create_user_endpoint()
    {
        $user = User::factory()->create();

        $data = [
            'first_name' => 'Christian',
            'last_name' => 'Sagastegui',
            'email' => 'csagastegui@mail.com',
            'password' => bcrypt('password'),
            'role' => 'recruiter',
            'company_id' => $user->company_id,
        ];

        $response = $this->post('/api/companies/'.$user->company_id.'/users', $data);

        $response->assertStatus(500);
    }

    public function test_it_should_allow_authenticated_user_to_show_a_user_information()
    {
        $relatedCompany = Company::factory()->create();

        $userToShow = User::factory()->create([
            'company_id' => $relatedCompany->id
        ]);

        $user = Sanctum::actingAs(
            User::factory()->create([
                'company_id' => $relatedCompany->id
            ]), ['api:admin']
        );

        $response = $this->get('/api/companies/'.$relatedCompany->id.'/users'.'/'.$userToShow->id);

        $response->assertStatus(200)
            ->assertJson([
            'status' => true,
            'message' => 'company user',
            ])
            ->assertJsonPath('user.id', $userToShow->id);
    }

    public function test_it_should_throw_an_error_if_unauthenticated_user_try_to_access_show_user_information_endpoint()
    {
        $relatedCompany = Company::factory()->create();

        $userToShow = User::factory()->create([
            'company_id' => $relatedCompany->id
        ]);

        $response = $this->get('/api/companies/'.$relatedCompany->id.'/users'.'/'.$userToShow->id);

        $response->assertStatus(500);
    }

    public function test_it_should_allow_authenticated_user_to_update_a_user()
    {
        $user = Sanctum::actingAs(
            User::factory()->create([
                'role' => 'company_admin'
            ]), ['company:admin']
        );

        $userToUpdate = User::factory()->create([
            'company_id' => $user->company_id
        ]);

        $data = [
            'first_name' => 'Christian',
            'last_name' => 'Sagastegui',
            'company_id' => $user->company_id,
            'email' => 'updatedmail@mail.com'
        ];

        $response = $this->patch('/api/companies/'.$user->company_id.'/users'.'/'.$userToUpdate->id, $data);

        $response->assertStatus(201)
            ->assertJson([
                'status' => true,
                'message' => 'User updated',
            ])
            ->assertJsonPath('user.id', $response['user']['id'])
            ->assertJsonPath('user.first_name', $data['first_name'])
            ->assertJsonPath('user.last_name', $data['last_name'])
            ->assertJsonPath('user.email', $data['email']);
    }

    public function test_it_should_throw_an_error_if_unauthenticated_user_try_to_access_update_user_endpoint()
    {
        $company = Company::factory()->create();

        $userToUpdate = User::factory()->create([
            'company_id' => $company->id
        ]);

        $data = [
            'first_name' => 'Christian',
            'last_name' => 'Sagastegui',
            'company_id' => $company->id,
            'email' => 'updatedmail@mail.com'
        ];

        $response = $this->patch('/api/companies/'.$company->id.'/users'.'/'.$userToUpdate->id, $data);

        $response->assertStatus(500);
    }

    public function test_it_should_allow_authenticated_user_to_delete_a_user()
    {
        $user = Sanctum::actingAs(
            User::factory()->create([
                'role' => 'company_admin'
            ]), ['company:admin']
        );

        $userToDelete = User::factory()->create([
            'company_id' => $user->company_id
        ]);

        $response = $this->delete('/api/companies/'.$user->company_id.'/users'.'/'.$userToDelete->id);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'user deleted',
            ]);
    }

    public function test_it_should_throw_an_error_if_unauthenticated_user_try_to_access_delete_user_endpoint()
    {
        $user = User::factory()->create();
        $userToDelete = User::factory()->create([
            'company_id' => $user->company_id
        ]);

        $response = $this->delete('/api/companies/'.$user->company_id.'/users'.'/'.$userToDelete->id);

        $response->assertStatus(500);
    }
}
