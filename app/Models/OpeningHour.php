<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpeningHour extends Model
{
    use HasFactory;

    protected $fillable = [
        'day_of_week',
        'start_time',
        'end_time',
    ];

    // Opzionale: helper per il giorno della settimana
    public function getDayNameAttribute()
    {
        $days = [
            'Domenica', 'Lunedì', 'Martedì', 'Mercoledì', 'Giovedì', 'Venerdì', 'Sabato'
        ];

        return $days[$this->day_of_week] ?? 'Sconosciuto';
    }
}
