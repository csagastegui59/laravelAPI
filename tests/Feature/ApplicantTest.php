<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use \App\Models\Api\{ User, Company, JobOpening, Applicant };

class ApplicantTest extends TestCase
{
    public function test_it_should_throw_an_error_to_unauthenticated_users_to_display_applicants_to_a_job_opening()
    {
        $company = Company::factory()->create();

        $jobOpening = JobOpening::factory()->create([
            'company_id' => $company->id
        ]);

        $applicant = Applicant::factory()->create([
            'job_opening_id' => $jobOpening->id
        ]);

        $response = $this->get('/api/companies/'.$company->id.'/jobOpenings'.'/'.$jobOpening->id.'/applicants');

        $response->assertStatus(500);
    }

    public function test_it_should_allow_api_admin_user_to_display_applicants_to_a_job_opening()
    {
        $user = Sanctum::actingAs(
            User::factory()->create(), ['company:admin']
        );

        $company = Company::where('id', $user->company_id)->first();

        $jobOpening = JobOpening::factory()->create([
            'company_id' => $company->id
        ]);

        $applicant = Applicant::factory()->create([
            'job_opening_id' => $jobOpening->id
        ]);

        $response = $this->get('/api/companies/'.$company->id.'/jobOpenings'.'/'.$jobOpening->id.'/applicants');

        $response->assertStatus(200)
        ->assertJson([
          'status' => true,
          'message' => $jobOpening->title.' applicants',
        ])
        ->assertJsonPath('applicants.0.id', $applicant->id);
    }

    public function test_it_should_allow_company_admin_user_to_display_applicants_to_a_job_opening()
    {
        $user = Sanctum::actingAs(
            User::factory()->create([
                'role' => 'company_admin'
            ]), ['company:admin']
        );

        $company = Company::where('id', $user->company_id)->first();

        $jobOpening = JobOpening::factory()->create([
            'company_id' => $company->id
        ]);

        $applicant = Applicant::factory()->create([
            'job_opening_id' => $jobOpening->id
        ]);

        $response = $this->get('/api/companies/'.$company->id.'/jobOpenings'.'/'.$jobOpening->id.'/applicants');

        $response->assertStatus(200)
        ->assertJson([
          'status' => true,
          'message' => $jobOpening->title.' applicants',
        ])
        ->assertJsonPath('applicants.0.id', $applicant->id);
    }

    public function test_it_should_allow_company_recruiter_user_to_display_applicants_to_a_job_opening()
    {
        $user = Sanctum::actingAs(
            User::factory()->create([
                'role' => 'recruiter'
            ]), ['company:recruiter']
        );

        $company = Company::where('id', $user->company_id)->first();

        $jobOpening = JobOpening::factory()->create([
            'company_id' => $company->id
        ]);

        $applicant = Applicant::factory()->create([
            'job_opening_id' => $jobOpening->id
        ]);

        $response = $this->get('/api/companies/'.$company->id.'/jobOpenings'.'/'.$jobOpening->id.'/applicants');

        $response->assertStatus(200)
        ->assertJson([
          'status' => true,
          'message' => $jobOpening->title.' applicants',
        ])
        ->assertJsonPath('applicants.0.id', $applicant->id);
    }

    // public function test_it_should_allow_unauthenticated_users_to_register_as_applicants()
    // {
    //     $company = Company::factory()->create();

    //     $jobOpening = JobOpening::factory()->create([
    //         'company_id' => $company->id
    //     ]);

    //     $response = $this->post(
    //         '/api/companies/'.$company->id.'/jobOpenings'.'/'.$jobOpening->id.'/applicants',
    //         [
    //             'first_name' => 'Christian',
    //             'last_name' => 'Sagastegui',
    //             'email' => 'mail@mail.com'
    //         ]
    //     );

    //     $response->assertStatus(201)
    //     ->assertJsonPath('applicant.id', $response['applicant']['id']);
    // }

    // public function test_it_should_allow_authenticated_users_to_register_as_applicants()
    // {
    //     $user = Sanctum::actingAs(
    //         User::factory()->create([
    //             'role' => 'recruiter'
    //         ]), ['company:recruiter']
    //     );

    //     $company = Company::factory()->create();

    //     $jobOpening = JobOpening::factory()->create([
    //         'company_id' => $company->id
    //     ]);

    //     $response = $this->post(
    //         '/api/companies/'.$company->id.'/jobOpenings'.'/'.$jobOpening->id.'/applicants',
    //         [
    //             'first_name' => 'Christian',
    //             'last_name' => 'Sagastegui',
    //             'email' => 'mail1@mail.com'
    //         ]
    //     );

    //     $response->assertStatus(201)
    //     ->assertJsonPath('applicant.id', $response['applicant']['id']);
    // }

    public function test_it_should_throw_an_error_to_unauthenticated_users_to_display_one_applicant_to_a_job_opening()
    {
        $company = Company::factory()->create();

        $jobOpening = JobOpening::factory()->create([
            'company_id' => $company->id
        ]);

        $applicant = Applicant::factory()->create([
            'job_opening_id' => $jobOpening->id
        ]);

        $response = $this->get('/api/companies/'.$company->id.'/jobOpenings'.'/'.$jobOpening->id.'/applicants'.'/'.$applicant->id);

        $response->assertStatus(500);
    }

    public function test_it_should_allow_authenticated_users_to_display_one_applicant_to_a_job_opening()
    {
        $user = Sanctum::actingAs(
            User::factory()->create(), ['company:admin']
        );

        $company = Company::where('id', $user->company_id)->first();

        $jobOpening = JobOpening::factory()->create([
            'company_id' => $company->id
        ]);

        $applicant = Applicant::factory()->create([
            'job_opening_id' => $jobOpening->id
        ]);

        $response = $this->get('/api/companies/'.$company->id.'/jobOpenings'.'/'.$jobOpening->id.'/applicants'.'/'.$applicant->id);

        $response->assertStatus(200)
        ->assertJson([
          'status' => true,
          'message' => 'Applicant',
        ])
        ->assertJsonPath('applicant.id', $applicant->id);
    }

    public function test_it_should_throw_an_error_to_unauthenticated_users_to_delete_one_applicant_to_a_job_opening()
    {
        $company = Company::factory()->create();

        $jobOpening = JobOpening::factory()->create([
            'company_id' => $company->id
        ]);

        $applicant = Applicant::factory()->create([
            'job_opening_id' => $jobOpening->id
        ]);

        $response = $this->delete('/api/companies/'.$company->id.'/jobOpenings'.'/'.$jobOpening->id.'/applicants'.'/'.$applicant->id);

        $response->assertStatus(500);
    }

    public function test_it_should_allow_authenticated_users_to_delete_one_applicant_to_a_job_opening()
    {
        $user = Sanctum::actingAs(
            User::factory()->create(), ['company:admin']
        );

        $company = Company::where('id', $user->company_id)->first();

        $jobOpening = JobOpening::factory()->create([
            'company_id' => $company->id
        ]);

        $applicant = Applicant::factory()->create([
            'job_opening_id' => $jobOpening->id
        ]);

        $response = $this->delete('/api/companies/'.$company->id.'/jobOpenings'.'/'.$jobOpening->id.'/applicants'.'/'.$applicant->id);

        $response->assertStatus(200)
        ->assertJson([
          'status' => true,
          'message' => 'applicant deleted',
        ]);
    }
}
