<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    protected $table = 'animales'; // Como he llamado al Model Animal he de especificar que la tabla es animales y no animals
    protected $primaryKey = 'id_animal';
    protected $fillable = [
        'nombre',
        'tipo',
        'peso',
        'enfermedad',
        'comentarios',
    ];
}
