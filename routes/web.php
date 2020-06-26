<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Ejemplo de rutas
Route::get('/api/pruebas', 'UserController@pruebas');


/*************RUTAS PARA PERFIL********/
// Utilizando rutas automaticas 
Route::resource('/api/perfil', 'PerfilController');

/*************RUTAS PARA LOS REGALOS********/
// Utilizando rutas automaticas
Route::resource('/api/regalos', 'RegalosController');

/*************RUTAS PARA LOS REGISTRADOS********/
// Utilizando rutas automaticas
Route::resource('/api/registrados', 'RegistradosController');
// para sacar datos de las conferencias por dia (pruebas con postMan en el body)
Route::get('/api/registrados/dias/{pases}', 'RegistradosController@pasesDia');

/*************RUTAS PARA INVITADOS********/
// Utilizando rutas automaticas 
Route::resource('/api/invitados', 'InvitadosController');
Route::post('/api/invitados/upload', 'InvitadosController@uploadImagen');
Route::get('/api/invitados/avatar/{filename}', 'InvitadosController@getImagen');
Route::get('/api/invitados/imagen/{filename}', 'InvitadosController@destroyImagen');

/*************RUTAS PARA EVENTOS********/
// Utilizando rutas automaticas 
Route::resource('/api/eventos', 'EventosController');

// ***********************************************************************************/

/*************RUTAS PARA BIENES RAICES********/
// USUSARIOS
// Ejemplo de rutas
Route::get('/api/pruebas', 'UserController@pruebas');

// POST
Route::post('/api/register', 'UserController@register');
Route::post('/api/login', 'UserController@loginAngular');
Route::post('/api/user/upload', 'UserController@uploadImagen')->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);

// PUT
Route::put('/api/user/update/{idUsuario}', 'UserController@updateAngular');

// GET
Route::get('/api/user/imagen/{nameImag}', 'UserController@destroyImagen');
Route::get('/api/user/avatar/{filename}', 'UserController@getImagen');
Route::get('/api/user/showUsuario/{id}', 'UserController@showUsuario');
Route::get('/api/user/indexUsuario', 'UserController@indexUsuario');

// DELETE
Route::delete('/api/user/destroyUsuario/{idUser}', 'UserController@destroyUsuario')->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::delete('/api/user/destroyPerfil/{idPerfil}', 'UserController@destroyPerfil')->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);

//RUTAS AUTOMATICAS

/*************RUTAS PARA CATEGORIABIENES********/
Route::resource('/api/categoria', 'CategoriaBienesController');

/*************RUTAS PARA CALEFONES********/
Route::resource('/api/calefones', 'CalefonesController');