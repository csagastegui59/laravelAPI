<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Api\{ Candidate, JobOpening };

class CandidateSeeder extends Seeder
{
    public function run()
    {
        Candidate::factory(3)->create();

        $jobOpening = JobOpening::factory()->create();
        Candidate::factory(3)->create([
            'job_opening_id' => $jobOpening->id
        ]);

        $secondJobOpening = JobOpening::factory()->create();
        Candidate::factory(3)->create([
            'job_opening_id' => $secondJobOpening->id
        ]);
    }
}
