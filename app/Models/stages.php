<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class stages extends Model
{

    use HasFactory;
    protected $table = 'stage';
    protected $fillable = [
        'idetud',
        'idens',
        'idens2',
        'idsal',
        'stage',
        'datestage',
        'heuredebut',
    ];

}
