<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'description',
        'color',
        'default_duration'
    ];

    // ➡️ Relazione: una categoria ha molti appuntamenti
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}