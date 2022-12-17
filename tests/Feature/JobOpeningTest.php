<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use \App\Models\Api\{ 
    User, 
    Company, 
    JobOpening, 
    Candidate 
};

class JobOpeningTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_it_should_allow_authenticated_user_to_access_report_endpoint()
    {
        $user = Sanctum::actingAs(
            User::factory()->create([
                'role' => 'company_admin'
            ]), ['company:admin']
        );

        $jobOpening = JobOpening::factory()->create([
            'company_id' => $user->company_id
        ]);

        $acceptedCandidates = Candidate::factory(3)->create([
            'application_status'=> 'accepted',
            'job_opening_id' => $jobOpening->id
        ]);

        $humanResourcesCandidates = Candidate::factory(5)->create([
            'application_status'=> 'human_resources',
            'job_opening_id' => $jobOpening->id
        ]);

        $engineeringCandidates = Candidate::factory(6)->create([
            'application_status'=> 'engineering',
            'job_opening_id' => $jobOpening->id
        ]);

        $response = $this->get('/api/companies/'.$user->company_id.'/jobOpenings'.'/'.$jobOpening->id.'/reports');

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Job opening report',
                'total_candidates' => 14,
                'accepted_candidates' => 3,
                'engineering_candidates' => 6,
                'human_resources_candidates' => 5
            ]);
    }

    public function test_it_should_not_allow_unauthenticated_user_to_access_report_endpoint()
    {
        $user = User::factory()->create();

        $jobOpening = JobOpening::factory()->create([
            'company_id' => $user->company_id
        ]);

        $response = $this->get('/api/companies/'.$user->company_id.'/jobOpenings'.'/'.$jobOpening->id.'/reports');

        $response->assertStatus(500);
    }

    public function test_it_should_allow_authenticated_user_to_access_show_all_job_openings_endpoint()
    {
        $user = Sanctum::actingAs(
            User::factory()->create([
                'role' => 'company_admin'
            ]), ['company:admin']
        );

        $company = Company::where('id', $user->company_id)->first();

        $jobOpening = JobOpening::factory(3)->create([
            'company_id' => $user->company_id
        ]);

        $response = $this->get('/api/companies/'.$user->company_id.'/jobOpenings');

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => $company->name.' Job Openings',
                'pagination' => [
                    'total_items' => 3
                ]
            ]);
    }

    public function test_it_should_not_allow_unauthenticated_user_to_access_show_all_job_openings_endpoint()
    {
        $user = User::factory()->create();

        $response = $this->get('/api/companies/'.$user->company_id.'/jobOpenings');

        $response->assertStatus(500);
    }

    public function test_it_should_allow_authenticated_user_to_create_a_job_opening()
    {
        $user = Sanctum::actingAs(
            User::factory()->create([
                'role' => 'company_admin'
            ]), ['company:admin']
        );

        $data = [
            'company_id' => $user->company_id,
            'title' => fake()->jobTitle(),
            'description' => fake()->realText($maxNbChars = 200, $indexSize = 2),
            'is_published' => fake()->numberBetween(0, 1)
        ];

        $response = $this->post('/api/companies/'.$user->company_id.'/jobOpenings', $data);

        $response->assertStatus(201)
            ->assertJson([
                'status' => true,
                'message' => 'Job opening successfuly created',
                'jobOpening' => [
                    'company_id' => $user->company_id,
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'is_published' => $data['is_published']
                ]
            ]);
    }

    public function test_it_should_not_allow_unauthenticated_user_to_create_a_job_opening()
    {
        $user = User::factory()->create();

        $data = [
            'company_id' => $user->company_id,
            'title' => fake()->jobTitle(),
            'description' => fake()->realText($maxNbChars = 200, $indexSize = 2),
            'is_published' => fake()->numberBetween(0, 1)
        ];

        $response = $this->post('/api/companies/'.$user->company_id.'/jobOpenings', $data);

        $response->assertStatus(500);
    }

    public function test_it_should_allow_authenticated_user_to_show_a_job_opening()
    {
        $user = Sanctum::actingAs(
            User::factory()->create([
                'role' => 'company_admin'
            ]), ['company:admin']
        );

        $jobOpening = JobOpening::factory()->create([
            'company_id' => $user->company_id
        ]);

        $response = $this->get('/api/companies/'.$user->company_id.'/jobOpenings'.'/'.$jobOpening->id);
        
        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Job opening',
                'jobOpening' => [
                    'id' => $jobOpening->id,
                    'company_id' => $user->company_id
                ]
            ]);
    }

    public function test_it_should_not_allow_unauthenticated_user_to_show_a_job_opening()
    {
        $user = User::factory()->create();

        $jobOpening = JobOpening::factory()->create([
            'company_id' => $user->company_id
        ]);

        $response = $this->get('/api/companies/'.$user->company_id.'/jobOpenings'.'/'.$jobOpening->id);
        
        $response->assertStatus(500);
    }

    public function test_it_should_allow_authenticated_user_to_update_a_job_opening()
    {
        $user = Sanctum::actingAs(
            User::factory()->create([
                'role' => 'company_admin'
            ]), ['company:admin']
        );

        $data = [
            'title' => fake()->jobTitle(),
            'description' => fake()->realText($maxNbChars = 200, $indexSize = 2),
            'is_published' => fake()->numberBetween(0, 1)
        ];

        $jobOpening = JobOpening::factory()->create([
            'company_id' => $user->company_id
        ]);

        $response = $this->patch('/api/companies/'.$user->company_id.'/jobOpenings'.'/'.$jobOpening->id, $data);

        $response->assertStatus(201)
            ->assertJson([
                'status'=> true,
                'message' => 'Job opening updated',
                'jobOpening' => [
                    'id' => $jobOpening->id,
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'is_published' => $data['is_published']
                ]
            ]);
    }

    public function test_it_should_not_allow_unauthenticated_user_to_update_a_job_opening()
    {
        $user = User::factory()->create();

        $data = [
            'title' => fake()->jobTitle(),
            'description' => fake()->realText($maxNbChars = 200, $indexSize = 2),
            'is_published' => fake()->numberBetween(0, 1)
        ];

        $jobOpening = JobOpening::factory()->create([
            'company_id' => $user->company_id
        ]);

        $response = $this->patch('/api/companies/'.$user->company_id.'/jobOpenings'.'/'.$jobOpening->id, $data);

        $response->assertStatus(500);
    }

    public function test_it_should_allow_authenticated_user_to_delete_a_job_opening()
    {
        $user = Sanctum::actingAs(
            User::factory()->create([
                'role' => 'company_admin'
            ]), ['company:admin']
        );

        $jobOpening = JobOpening::factory()->create([
            'company_id' => $user->company_id
        ]);

        $response = $this->delete('/api/companies/'.$user->company_id.'/jobOpenings'.'/'.$jobOpening->id);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Job opening deleted',
            ]);
    }
    
    public function test_it_should_not_allow_unauthenticated_user_to_delete_a_job_opening()
    {
        $user = User::factory()->create();

        $jobOpening = JobOpening::factory()->create([
            'company_id' => $user->company_id
        ]);

        $response = $this->delete('/api/companies/'.$user->company_id.'/jobOpenings'.'/'.$jobOpening->id);

        $response->assertStatus(500);
    }
}
