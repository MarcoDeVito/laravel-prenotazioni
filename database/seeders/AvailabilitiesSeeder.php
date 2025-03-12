<?php

namespace Database\Seeders;

use App\Models\Availability;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AvailabilitiesSeeder extends Seeder
{
    public function run()
    {
        // Disponibilità: Lunedì-Venerdì 09:00-13:00 e 14:00-18:00
        $days = [1, 2, 3, 4, 5]; // Lun-Ven

        foreach ($days as $day) {
            // Mattina
            Availability::create([
                'day_of_week' => $day,
                'start_time' => '09:00:00',
                'end_time' => '13:00:00'
            ]);

            // Pomeriggio
            Availability::create([
                'day_of_week' => $day,
                'start_time' => '14:00:00',
                'end_time' => '18:00:00'
            ]);
        }

        // Sabato solo mattina
        Availability::create([
            'day_of_week' => 6, // Sabato
            'start_time' => '09:00:00',
            'end_time' => '12:00:00'
        ]);
    }
}