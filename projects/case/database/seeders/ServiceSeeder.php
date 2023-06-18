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
                'name' => 'Brezilya Fönü',
                'duration' => '120'
            ],
            [
                'name' => 'Manikür',
                'duration' => '35'
            ],
            [
                'name' => 'Pedikür',
                'duration' => '40'
            ],
            [
                'name' => 'Saç Kesimi',
                'duration' => '100'
            ],
            [
                'name' => 'Sakal Kesimi',
                'duration' => '30'
            ],
            [
                'name' => 'Gelin Başı',
                'duration' => '120'
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }

    }
}
