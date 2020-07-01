<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Imagen extends Model
{
    // Nuestro modelo para la base de datos

    // 1.- indicamos la tabla que va a utilizar de la base de datos
    protected $table = 'imagen';

    // Imagen recibe a propiedades
    public function propiedades()
    {
        return $this->belongsTo('App\Propiedades', 'propiedades_id');
    }
}