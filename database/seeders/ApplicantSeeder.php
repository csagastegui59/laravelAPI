<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Api\{ Applicant, JobOpening };

class ApplicantSeeder extends Seeder
{
    public function run()
    {
        Applicant::factory(3)->create();

        $jobOpening = JobOpening::factory()->create();
        Applicant::factory(3)->create([
            'job_opening_id' => $jobOpening->id
        ]);

        $secondJobOpening = JobOpening::factory()->create();
        Applicant::factory(3)->create([
            'job_opening_id' => $secondJobOpening->id
        ]);
    }
}
