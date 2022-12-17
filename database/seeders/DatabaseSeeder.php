<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use \Database\Seeders\{
    CompanySeeder,
    UserSeeder,
    JobOpeningSeeder,
    CandidateSeeder,
    ApplicantSeeder,
};

class DatabaseSeeder extends Seeder
{

    public function run()
    {
        $this->call([
            CompanySeeder::class,
            UserSeeder::class,
            JobOpeningSeeder::class,
            CandidateSeeder::class,
            ApplicantSeeder::class,
          ]);
    }
}
