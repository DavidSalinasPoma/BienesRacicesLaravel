<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    // Nuestro modelo para la base de datos

    // 1.- indicamos la tabla que va a utilizar de la base de datos
    protected $table = 'empresa';

    public function usuarios()
    {
        return $this->belongsTo('App\User', 'usuarios_id'); // Empresa Recibe a Usuario
    }
}