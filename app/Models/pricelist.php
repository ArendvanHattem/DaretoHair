<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class pricelist extends Model
{
    use HasFactory;
    protected $fillable = ['service','description','amount'];
}
