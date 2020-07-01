<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImagenUser extends Model
{
    // Nuestro modelo para la base de datos

    // 1.- indicamos la tabla que va a utilizar de la base de datos
    protected $table = 'imagenuser';

    // Imagen recibe a propiedades
    public function usuarios()
    {
        return $this->belongsTo('App\Usuarios', 'usuarios_id');
    }
}