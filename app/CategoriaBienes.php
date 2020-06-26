<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoriaBienes extends Model
{
    // Nuestro modelo para la base de datos

    // 1.- indicamos la tabla que va a utilizar de la base de datos
    protected $table = 'categoria';


    // Es una relacion de UNO a MUCHOS(Una categoria puede estar asignada a muchas Propiedades)
    public function propiedades()
    {
        return $this->hasMany('App\Propiedades'); // se dirige hacia Propiedades, sera usado por propiedades
    }

    // relacion de uno a muchos inversa(muchos a uno)
    public function usuarios()
    {
        return $this->belongsTo('App\User', 'usuarios_id'); // CategoriaBienes Recibe a Usuario
    }
}