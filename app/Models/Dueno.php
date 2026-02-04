<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Animal;

class Dueno extends Model
{
    protected $primaryKey = 'id_persona';
    protected $fillable = [
        'nombre',
        'apellido',
        'id_animal',
    ];
}
