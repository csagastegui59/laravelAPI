<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Api\{JobOpening, Company };

class JobOpeningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        JobOpening::factory(3)->create();

        $company = Company::factory()->create();
        JobOpening::factory(3)->create([
            'company_id' => $company->id
        ]);

        $secondCompany = Company::factory()->create();
        JobOpening::factory(3)->create([
            'company_id' => $secondCompany->id
        ]);
    }
}
