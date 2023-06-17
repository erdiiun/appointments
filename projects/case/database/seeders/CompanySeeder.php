<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = [
            [
                'name' => 'A Beauty',
                'address' => 'A Beauty Address',
                'email' => 'a@email.com',
                'country' => 255,
                'city' => 10,
                'district' => 10,
                'status' => 1
            ],
            [
                'name' => 'B Beauty',
                'address' => 'B Beauty Address',
                'email' => 'b@email.com',
                'country' => 255,
                'city' => 10,
                'district' => 10,
                'status' => 1
            ],
            [
                'name' => 'C Beauty',
                'address' => 'C Beauty Address',
                'email' => 'c@email.com',
                'country' => 255,
                'city' => 10,
                'district' => 10,
                'status' => 1
            ],
            [
                'name' => 'D Beauty',
                'address' => 'D Beauty Address',
                'email' => 'd@email.com',
                'country' => 255,
                'city' => 10,
                'district' => 10,
                'status' => 1
            ],
            [
                'name' => 'E Beauty',
                'address' => 'E Beauty Address',
                'email' => 'e@email.com',
                'country' => 255,
                'city' => 10,
                'district' => 10,
                'status' => 1
            ],
        ];

        foreach ($companies as $company) {
            Company::create($company);
        }
    }
}
