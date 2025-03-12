<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Visita Medica',
                'description' => 'Controllo generale o visita specialistica',
                'color' => '#28a745', // verde
                'default_duration' => 30
            ],
            [
                'name' => 'Consulenza Legale',
                'description' => 'Consulenza con avvocato',
                'color' => '#007bff', // blu
                'default_duration' => 60
            ],
            [
                'name' => 'Coaching',
                'description' => 'Sessione motivazionale o business coaching',
                'color' => '#ffc107', // giallo
                'default_duration' => 45
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}