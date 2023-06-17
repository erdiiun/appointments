<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'service_name' => 'Brezilya Fönü',
                'duration' => '120'
            ],
            [
                'service_name' => 'Manikür',
                'duration' => '35'
            ],
            [
                'service_name' => 'Pedikür',
                'duration' => '40'
            ],
            [
                'service_name' => 'Saç Kesimi',
                'duration' => '100'
            ],
            [
                'service_name' => 'Sakal Kesimi',
                'duration' => '30'
            ],
            [
                'service_name' => 'Gelin Başı',
                'duration' => '120'
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }

    }
}
