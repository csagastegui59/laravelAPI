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

class CandidateTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_it_should_allow_authenticated_user_to_show_all_candidates_from_a_job_opening()
    {
        $user = Sanctum::actingAs(
            User::factory()->create([
                'role' => 'company_admin'
            ]), ['company:admin']
        );

        $jobOpening = JobOpening::factory()->create([
            'company_id' => $user->company_id
        ]);

        $candidate = Candidate::factory()->create([
            'job_opening_id' => $jobOpening->id
        ]);
        
        $response = $this->get('/api/companies/'.$user->company_id.'/jobOpenings'.'/'.$jobOpening->id.'/candidates');

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => $jobOpening->title . ' candidates',
            ])
            ->assertJsonPath('pagination.total_items', 1)
            ->assertJsonPath('candidates.0.id', $candidate->id);
    }

    public function test_it_should_throw_an_error_if_unauthenticated_user_try_to_access_show_candidates_endpoint()
    {
        $user = User::factory()->create();

        $jobOpening = JobOpening::factory()->create([
            'company_id' => $user->company_id
        ]);

        $candidate = Candidate::factory()->create([
            'job_opening_id' => $jobOpening->id
        ]);
        
        $response = $this->get('/api/companies/'.$user->company_id.'/jobOpenings'.'/'.$jobOpening->id.'/candidates');

        $response->assertStatus(500);
    }

    public function test_it_should_allow_authenticated_user_to_create_a_candidate()
    {
        $user = Sanctum::actingAs(
            User::factory()->create([
                'role' => 'company_admin'
            ]), ['company:admin']
        );

        $jobOpening = JobOpening::factory()->create([
            'company_id' => $user->company_id
        ]);

        $data = [
            'first_name' => 'Christian',
            'last_name' => 'Sagastegui',
            'email' => fake()->unique()->safeEmail(),
            'password' => bcrypt('password'),
            'application_status' => 'human_resources',
            'job_opening_id' => $jobOpening->id,
        ];

        $response = $this->post('/api/companies/'.$user->company_id.'/jobOpenings'.'/'.$jobOpening->id.'/candidates', $data);

        $response->assertStatus(201)
            ->assertJson([
                'status' => true,
                'message' => 'Candidate successfuly created',
            ])
            ->assertJsonPath('candidate.id', $response['candidate']['id']);
    }

    public function test_it_should_throw_an_error_if_unauthenticated_user_try_to_register_a_candidate()
    {
        $user = User::factory()->create();

        $jobOpening = JobOpening::factory()->create([
            'company_id' => $user->company_id
        ]);

        $data = [
            'first_name' => 'Christian',
            'last_name' => 'Sagastegui',
            'email' => fake()->unique()->safeEmail(),
            'password' => bcrypt('password'),
            'application_status' => 'human_resources',
            'job_opening_id' => $jobOpening->id,
        ];

        $response = $this->post('/api/companies/'.$user->company_id.'/jobOpenings'.'/'.$jobOpening->id.'/candidates', $data);

        $response->assertStatus(500);
    }

    public function test_it_should_allow_authenticated_user_to_show_a_candidate()
    {
        $user = Sanctum::actingAs(
            User::factory()->create([
                'role' => 'company_admin'
            ]), ['company:admin']
        );

        $jobOpening = JobOpening::factory()->create([
            'company_id' => $user->company_id
        ]);

        $candidate = Candidate::factory()->create([
            'job_opening_id' => $jobOpening->id
        ]);

        $response = $this->get('/api/companies/'.$user->company_id.'/jobOpenings'.'/'.$jobOpening->id.'/candidates'.'/'.$candidate->id);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Candidate',
            ])
            ->assertJsonPath('candidate.id', $candidate->id);
    }

    public function test_it_should_not_allow_unauthenticated_user_to_show_a_candidate()
    {
        $user = User::factory()->create();

        $jobOpening = JobOpening::factory()->create([
            'company_id' => $user->company_id
        ]);

        $candidate = Candidate::factory()->create([
            'job_opening_id' => $jobOpening->id
        ]);

        $response = $this->get('/api/companies/'.$user->company_id.'/jobOpenings'.'/'.$jobOpening->id.'/candidates'.'/'.$candidate->id);

        $response->assertStatus(500);
    }

    public function test_it_should_allow_authenticated_user_to_update_a_candidate()
    {
        $user = Sanctum::actingAs(
            User::factory()->create([
                'role' => 'company_admin'
            ]), ['company:admin']
        );

        $jobOpening = JobOpening::factory()->create([
            'company_id' => $user->company_id
        ]);

        $candidate = Candidate::factory()->create([
            'job_opening_id' => $jobOpening->id
        ]);

        $data = [
            'first_name' => fake()->name(),
            'last_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'application_status' => fake()->randomElement(['human_resources', 'engineering', 'accepted']),
            'job_opening_id' => $jobOpening->id,
        ];

        $response = $this->patch('/api/companies/'.$user->company_id.'/jobOpenings'.'/'.$jobOpening->id.'/candidates'.'/'.$candidate->id, $data);

        $response->assertStatus(201)
            ->assertJson([
                'status' => true,
                'message' => 'Candidate updated',
            ])
            ->assertJsonPath('candidate.id', $candidate->id)
            ->assertJsonPath('candidate.job_opening_id', $jobOpening->id)
            ->assertJsonPath('candidate.application_status', $data['application_status'])
            ->assertJsonPath('candidate.email', $data['email']);
    }

    public function test_it_should_not_allow_unauthenticated_user_to_update_a_candidate()
    {
        $user = User::factory()->create();

        $jobOpening = JobOpening::factory()->create([
            'company_id' => $user->company_id
        ]);

        $candidate = Candidate::factory()->create([
            'job_opening_id' => $jobOpening->id
        ]);

        $data = [
            'first_name' => fake()->name(),
            'last_name' => fake()->name(),
        ];

        $response = $this->patch('/api/companies/'.$user->company_id.'/jobOpenings'.'/'.$jobOpening->id.'/candidates'.'/'.$candidate->id, $data);

        $response->assertStatus(500);
    }

    public function test_it_should_allow_authenticated_user_to_delete_a_candidate()
    {
        $user = Sanctum::actingAs(
            User::factory()->create([
                'role' => 'company_admin'
            ]), ['company:admin']
        );

        $jobOpening = JobOpening::factory()->create([
            'company_id' => $user->company_id
        ]);

        $candidate = Candidate::factory()->create([
            'job_opening_id' => $jobOpening->id
        ]);

        $response = $this->delete('/api/companies/'.$user->company_id.'/jobOpenings'.'/'.$jobOpening->id.'/candidates'.'/'.$candidate->id);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'candidate deleted',
            ]);
    }

    public function test_it_should_not_allow_unauthenticated_user_to_delete_a_candidate()
    {
        $user = User::factory()->create();

        $jobOpening = JobOpening::factory()->create([
            'company_id' => $user->company_id
        ]);

        $candidate = Candidate::factory()->create([
            'job_opening_id' => $jobOpening->id
        ]);

        $response = $this->delete('/api/companies/'.$user->company_id.'/jobOpenings'.'/'.$jobOpening->id.'/candidates'.'/'.$candidate->id);

        $response->assertStatus(500);
    }
}
