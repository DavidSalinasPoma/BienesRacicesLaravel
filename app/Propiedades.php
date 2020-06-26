<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Propiedades extends Model
{
    // Nuestro modelo para la base de datos

    // 1.- indicamos la tabla que va a utilizar de la base de datos
    protected $table = 'propiedades';

    // Propiedades recibe a usuarios
    public function usuarios()
    {
        return $this->belongsTo('App\User', 'usuarios_id'); // Propiedades Recibe a Usuario
    }

    // Propiedades recibe a CategoriaBienes
    public function categoriaBienes()
    {
        return $this->belongsTo('App\CategoriaBienes', 'categoria_id'); // Propiedades Recibe a CategoriaBienes
    }
}