<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'user_id',  
        'hairdresser_id',
        'service', 
        'appointment_date',
        'duration', // toegevoegd
        'notes',
        'status'
    ];
    
    protected $casts = [
        'appointment_date' => 'datetime'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hairdresser()
    {
        return $this->belongsTo(Hairdresser::class);
    }
}