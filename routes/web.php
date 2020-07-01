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

// Route::get('/login', function () {
//     return view('welcome');
// });

// Ejemplo de rutas
// Route::get('/api/loginPrueba', 'UserController@login');

/*************RUTAS PARA INVITADOS********/
// Utilizando rutas automaticas 
// Route::resource('/api/invitados', 'InvitadosController');
// Route::post('/api/invitados/upload', 'InvitadosController@uploadImagen');
// Route::get('/api/invitados/avatar/{filename}', 'InvitadosController@getImagen');
// Route::get('/api/invitados/imagen/{filename}', 'InvitadosController@destroyImagen');


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
Route::get('/api/user/avatar/{filename}', 'UserController@getImagen');
Route::get('/api/user/showUsuario/{id}', 'UserController@showUsuario');
Route::get('/api/user/indexUsuario', 'UserController@indexUsuario');

// DELETE
Route::delete('/api/user/destroyUsuario/{idUser}', 'UserController@destroyUsuario')->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::delete('/api/user/destroyPerfil/{idPerfil}', 'UserController@destroyPerfil')->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::delete('/api/user/imagen/{nameImag}', 'UserController@destroyImagen');

//RUTAS AUTOMATICAS

/*************RUTAS PARA CATEGORIABIENES********/
Route::resource('/api/categoria', 'CategoriaBienesController');

/*************RUTAS PARA CALEFONES********/
Route::resource('/api/calefones', 'CalefonesController');
Route::post('/api/calefones/uploadImagen', 'CalefonesController@uploadImagen');
Route::get('/api/calefones/getImagen/{filename}', 'CalefonesController@getImagen');
Route::delete('/api/calefones/destroyImagen/{filename}', 'CalefonesController@destroyImagen');

/*************RUTAS PARA EMPRESA********/
Route::resource('/api/empresa', 'EmpresaController');
Route::post('/api/empresa/uploadImagen', 'EmpresaController@uploadImagen');
Route::get('/api/empresa/getImagen/{filename}', 'EmpresaController@getImagen');
Route::delete('/api/empresa/destroyImagen/{filename}', 'EmpresaController@destroyImagen');

/*************RUTAS PARA PROPIEDAD********/
Route::resource('/api/propiedad', 'PropiedadesController');


/*************login para guardar el primer administrador********/