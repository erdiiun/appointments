<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\CompanyWorkDay;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanyWorkDaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = Company::all();

        foreach ($companies as $company) {
            for ($i = 1; $i <= 6; $i++) {
                CompanyWorkDay::create([
                    'company_id' => $company->id,
                    'day_of_week' => $i,
                    'start_time' => '09:00',
                    'end_time' => '19:00',
                ]);
            }
        }
    }
}
