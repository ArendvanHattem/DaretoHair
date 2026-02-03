<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teamlid extends Model
{
    protected $table = 'teamleden';
    protected $fillable = ['naam', 'speciality', 'photo'];
}
