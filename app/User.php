<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    // INICIO DE MODELOS
    protected $table = 'usuarios';
    // 1.- Un usuario puede sacar todos los categorias asignados o todo lo que haya creado el
    // es una relacion de UNO a MUCHOS OJO
    public function usuarios()
    {
        // Se indica el modelo
        return $this->hasMany('App\User');
    }

    public function categoriaBienes()
    {
        return $this->hasMany('App\CategoriaBienes'); // El Usuario se dirige a CategoriaBienes
    }

    public function Propiedades()
    {
        return $this->hasMany('App\Propiedades'); // El Usuario se dirige a Propiedades
    }

    public function Calefones()
    {
        return $this->hasMany('App\Calefones'); // El Usuario se dirige a Calefones
    }

    public function Empresa()
    {
        return $this->hasMany('App\Empresa'); // El Usuario se dirige a Empresa
    }

    // Campos que se puede insertar, eliminar y actulizar
    protected $fillable = [
        'carnet', 'nombres', 'apellidos', 'imagen', 'userpass', 'password', 'email', 'perfil', 'estado', 'remember_token'
    ];

    /**
     * Campos que no devuelve el api, simepre van a estar bloqueados
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}