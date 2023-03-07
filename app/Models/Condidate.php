<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Condidate extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'nom',
        'prenom',
      
        'cin',
        'parcours',
        'groupe',
        'stage',
    ];
}
