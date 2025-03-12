<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'start',
        'end',
        'status',
        'color'
    ];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
    ];

    // ➡️ Relazione: l'appuntamento appartiene a un utente (cliente)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ➡️ Relazione: l'appuntamento appartiene a una categoria
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
