<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanyServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = Company::all();
        $services = Service::all();

        foreach ($companies as $company) {
            $randomServices = $services->random(rand(1, 5))->pluck('id');
            $company->services()->attach($randomServices,
                [
                    'duration' => rand(25, 150),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
        }
    }
}
