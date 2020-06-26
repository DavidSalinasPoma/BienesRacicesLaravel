<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Calefones extends Model
{
    // Nuestro modelo para la base de datos

    // 1.- indicamos la tabla que va a utilizar de la base de datos
    protected $table = 'calefones';

    // Calefones recibe a usuario
    public function usuarios()
    {
        return $this->belongsTo('App\User', 'usuarios_id'); // Calefones recibe a usuario
    }
}