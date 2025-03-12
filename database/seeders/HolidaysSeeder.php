<?php

namespace Database\Seeders;

use App\Models\Holiday;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HolidaysSeeder extends Seeder
{
    public function run()
    {
        $holidays = [
            [
                'date' => '2025-12-25',
                'reason' => 'Natale'
            ],
            [
                'date' => '2025-12-26',
                'reason' => 'Santo Stefano'
            ],
            [
                'date' => '2025-01-01',
                'reason' => 'Capodanno'
            ]
        ];

        foreach ($holidays as $holiday) {
            Holiday::create($holiday);
        }
    }
}