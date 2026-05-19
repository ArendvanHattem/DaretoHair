<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'klant_id',
        'medewerker_id',
        'service',
        'appointment_date',
        'duration', // toegevoegd
        'notes',
        'status'
    ];

    protected $casts = [
        'appointment_date' => 'datetime'
    ];

    public function klant()
    {
        return $this->belongsTo(User::class, 'klant_id');
    }

    // Relatie naar de Medewerker (User)
    public function medewerker()
    {
        return $this->belongsTo(User::class, 'medewerker_id');
    }
}
