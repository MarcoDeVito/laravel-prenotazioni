<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OpeningHour;

class OpeningHourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pulizia della tabella prima di popolare (opzionale ma consigliato)
        OpeningHour::truncate();

        // Array di giorni con i relativi orari
        $openingHours = [
            // Lunedì → 9:00-18:00
            [
                'day_of_week' => 1, // Lunedì
                'start_time' => '09:00:00',
                'end_time' => '18:00:00',
            ],
            // Martedì → 9:00-18:00
            [
                'day_of_week' => 2, // Martedì
                'start_time' => '09:00:00',
                'end_time' => '18:00:00',
            ],
            // Mercoledì → 9:00-18:00
            [
                'day_of_week' => 3, // Mercoledì
                'start_time' => '09:00:00',
                'end_time' => '18:00:00',
            ],
            // Giovedì → 9:00-18:00
            [
                'day_of_week' => 4, // Giovedì
                'start_time' => '09:00:00',
                'end_time' => '18:00:00',
            ],
            // Venerdì → 9:00-18:00
            [
                'day_of_week' => 5, // Venerdì
                'start_time' => '09:00:00',
                'end_time' => '18:00:00',
            ],
            // Sabato → 9:00-13:00
            [
                'day_of_week' => 6, // Sabato
                'start_time' => '09:00:00',
                'end_time' => '13:00:00',
            ],
            // Domenica → CHIUSO (non inserisco record)
        ];

        // Creazione dei record
        foreach ($openingHours as $hour) {
            OpeningHour::create($hour);
        }

        $this->command->info('Orari di apertura settimanali inseriti con successo!');
    }
}
