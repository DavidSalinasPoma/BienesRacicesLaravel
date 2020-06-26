<?php

namespace App\Http\Controllers;

use App\Propiedades;
use Exception;
use App\Helpers\JwtAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;


class PropiedadesController extends Controller
{
    // Metodo constructor
    public function __construct()
    {
        // Utiliza la autenticacion en toda la clase excepto en los metodos de index y show.
        $this->middleware('api.auth', ['except' => ['index', 'show', 'getImagen', 'destroyImagen']]);
    }

    // INDEX sirve para sacar todos los registro de propiedades la base de datos
    public function index()
    {
        $categoria = Propiedades::all()->load('usuarios');
        $data = array(
            'code' => 200,
            'status' => 'success',
            'categoria' => $categoria
        );
        return response()->json($data, $data['code']);
    }

    // SHOW metodo para mostrar un solo registro de propiedad
    public function show($idPropiedad)
    {
        $propiedad = Propiedades::find($idPropiedad);

        // Comprobamos si es un objeto eso quiere decir si exist en la base de datos.
        if (is_object($propiedad)) {
            $data = array(
                'code' => 200,
                'status' => 'success',
                'calefon' => $propiedad
            );
        } else {
            $data = array(
                'code' => 404,
                'status' => 'error',
                'message' => 'La propiedad que busca no existe'
            );
        }
        return response()->json($data, $data['code']);
    }

    // STORE Permite guardar los datos de en la base de datos
    // Metodos de comportamiento Con este parametro recibimos todo de Angular
    public function store(Request $request)
    {
        // 1.- RECIBIR DATOS
        // Recibimos los datos de angular en una variable
        $json = $request->input('json', null);

        // Convertimos los datos en objeto y array
        $params = json_decode($json); // objeto
        $paramsArray = json_decode($json, true); // Array


        // Validamos si esta vacio
        if (!empty($params) && !empty($paramsArray)) {
            // Limpiar datos de espacios en blanco al principio y el final
            // $paramsArray = array_map('trim', $paramsArray);

            // 2.-VALIDAR DATOS
            $validate = Validator::make($paramsArray, [
                'titulo' => 'required|unique:calefones',
                'categoria_id' => 'required',
                'tipo_propiedad' => 'required',
                'precio' => 'required|numeric',
                'descripcion' => 'required',
                'imagen' => 'required',
                'img_principal' => 'required',
                'direccion' => 'required',
                'dormitorios' => 'required|numeric',
                'banios' => 'required|numeric',
                'garage' => 'required|numeric',
                'area_total' => 'required|numeric',
                'area_construida' => 'required|numeric',
                'perfil_frente' => 'required|numeric',
                'perfil_fondo' => 'required|numeric',
                'caracteristicas' => 'required',
            ]);

            // 5.- SI LA VALIDACION FUE CORRECTA
            // Comprobar si los datos son validos
            if ($validate->fails()) { // en caso si los datos fallan la validacion
                // La validacion ha fallado
                $data = array(
                    'status' => 'error',
                    'code' => 400,
                    'message' => 'Los datos no son validos',
                    'errors' => $validate->errors()
                );
            } else {

                // CONSEGUIR EL USUARIO IDENTIFICADO->El que hace el registro.
                $jwtAuth = new JwtAuth();
                $token = $request->header('authorization', null);
                $user = $jwtAuth->checkToken($token, true); // Devuelve el token decodificado en un objeto.

                // Si la validacion pasa correctamente  
                // Crear el objeto usuario para guardar en la base de datos
                $propiedades = new Propiedades();
                $propiedades->titulo = $paramsArray['titulo'];
                $propiedades->categoria_id = $paramsArray['categoria_id'];
                $propiedades->tipo_propiedad = $paramsArray['tipo_propiedad'];
                $propiedades->descripcion = $paramsArray['descripcion'];
                $propiedades->imagen = $paramsArray['imagen'];
                $propiedades->img_principal = $paramsArray['img_principal'];
                $propiedades->direccion = $paramsArray['direccion'];
                $propiedades->dormitorios = $paramsArray['dormitorios'];
                $propiedades->banios = $paramsArray['banios'];
                $propiedades->garage = $paramsArray['garage'];
                $propiedades->area_total = $paramsArray['area_total'];
                $propiedades->area_construida = $paramsArray['area_construida'];
                $propiedades->perfil_frente = $paramsArray['perfil_frente'];
                $propiedades->perfil_fondo = $paramsArray['perfil_fondo'];
                $propiedades->caracteristicas = $paramsArray['caracteristicas'];
                $propiedades->usuarios_id = $user->sub;
                // 7.-GUARDAR EN LA BASE DE DATOS
                $propiedades->save();
                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'El registro se creo correctamente correctamente',
                );
            }
        } else {
            $data = array(
                'status' => 'Error',
                'code' => 404,
                'message' => 'Los datos no son validos'
            );
        }
        return response()->json($data, $data['code']);
    }

    // Metodo para actualizar los datos del calefon
    public function update($idPropiedad, Request $request)
    {
        // la utenticacion se hara de forma automatica
        // 1.- Recoger los datos por post.
        $json = $request->input('json', null);
        $paramsArray = json_decode($json, true); // convierte un json en array

        // Validamos lo que nos llega que no este vacio
        if (!empty($paramsArray)) {
            // 2.- Validar los datos.
            // 2.-VALIDAR DATOS
            $validate = Validator::make($paramsArray, [
                // 'titulo' => 'required|unique:calefones',
                'categoria_id' => 'required',
                'tipo_propiedad' => 'required',
                'precio' => 'required|numeric',
                'descripcion' => 'required',
                'imagen' => 'required',
                'img_principal' => 'required',
                'direccion' => 'required',
                'dormitorios' => 'required|numeric',
                'banios' => 'required|numeric',
                'garage' => 'required|numeric',
                'area_total' => 'required|numeric',
                'area_construida' => 'required|numeric',
                'perfil_frente' => 'required|numeric',
                'perfil_fondo' => 'required|numeric',
                'caracteristicas' => 'required',
            ]);
            // Comprobar si los datos son validos
            if ($validate->fails()) { // en caso si los datos fallan la validacion
                // La validacion ha fallado
                $data = array(
                    'status' => 'Error',
                    'code' => 404,
                    'message' => 'Los datos no son validos',
                    'errors' => $validate->errors()
                );
            } else {
                // 3.- Quitar lo que no quiero actualizar
                unset($paramsArray['id']);
                unset($paramsArray['created_at']);
                unset($paramsArray['usuarios_id']);
                // 4.- actualizar el personal en la base de datos
                try {
                    $propiedades = Propiedades::where('id', $idPropiedad)->update($paramsArray);
                    $data = array(
                        'status' => 'success',
                        'code' => 200,
                        'message' => 'Se ha actualizado correctamente.',
                        'perfil' => $paramsArray
                    );
                } catch (Exception $e) {
                    $data = array(
                        'status' => 'error',
                        'code' => 400,
                        'message' => 'Ya existe un registro con este titulo',
                        // 'error' => $e
                    );
                }
            }
        } else {
            $data = array(
                'status' => 'error',
                'code' => 400,
                'message' => 'No hay datos para actualizar'
            );
        }
        // 5.- Devolver la respuesta
        return response()->json($data, $data['code']);
    }

    // Metodo para eliminar un registro del PERFIL
    public function destroy($idPropiedad, Request $request)
    {
        // 1.- conseguir el registro
        $propiedades = Propiedades::find($idPropiedad);

        // 2.- borrar el registro
        $propiedades->delete();

        // 3.- Devolver la respuesta.
        $data = array(
            'status' => 'success',
            'code' => 200,
            'message' => 'El registro ha sido eliminado correctamente',
            'personal' => $propiedades
        );

        return response()->json($data, $data['code']);
    }

    // Seccion trabajar con imagenes


    // Metodo para subir una imagen y sacar el nombre a el disco duro de laravel desde Angular
    public function uploadImagen(Request $request)
    {
        // Para evitar utlizar el mismo codigo para la autenticacion se debe utilizar un middleware
        // php artisan make:middleware 
        // es un metodo que se ejecuta antes del controlador es como un filtro.

        // 1.- Recoger la imagen desde angular
        $imagen = $request->file('file0'); //Segun angular

        // Validar que solo lleguen imagenes
        $validate = Validator::make($request->all(), [
            // Archivos que se va a permitir
            'file0' => 'required|image|mimes:jpg,jpeg,png,gif,JPG'
        ]);

        // 2.- Guardar la imagen
        // comprobar si la imagen llega o falla la validacion.
        if (!$imagen || $validate->fails()) {

            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'Error al subir imagen',
                'imagen' => $imagen
            );
        } else {

            $imageName = time() . $imagen->getClientOriginalName(); // saca el nombre del la imagen.
            // crear carpeta users luego conf/filesystems.php
            Storage::disk('propiedades')->put($imageName, File::get($imagen)); // Guarda la imagen en el disco laravel

            // 3.- Delvolver el resultado.
            $data = array(
                'code' => 200,
                'status' => 'success',
                'image' => $imageName
            );
        }
        return response()->json($data, $data['code']); // devuelve un objeto json.
    }

    // Metodo para sacar la imagen del backent para Angular
    public function getImagen($fileName) // recibe el nombre del archivo imagen por parametro.
    {
        // comprobar si un archivo existe
        $isset = Storage::disk('propiedades')->exists($fileName);

        if ($isset) {
            $file = Storage::disk('propiedades')->get($fileName); // Guarda el archivo encontrado.
            return new Response($file, 200);
        } else {
            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'La imagen no existe'
            );
            return response()->json($data, $data['code']); //Devuelve la imagen encontrada
        }
    }

    // Metodo que elimina una imagen del se servidor
    public function destroyImagen($nameImag)
    {
        if ($nameImag != 'no-image.png') {
            // comprobar si un archivo existe
            $isset = Storage::disk('propiedades')->exists($nameImag);
            if ($isset) {
                $file = Storage::disk('propiedades')->delete($nameImag); // Elimina el archivo encontrado.
                $data = array(
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'La imagen se elimino correctamente'
                );
            } else {
                $data = array(
                    'code' => 400,
                    'status' => 'error',
                    'message' => 'La imagen no existe dsp'
                );
            }
            return response()->json($data, $data['code']); //Devuelve la imagen encontrada
        }
    }
}