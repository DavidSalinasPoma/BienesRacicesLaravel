<?php

namespace App\Http\Controllers;

use App\Empresa;
use Exception;
use App\Helpers\JwtAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;

class EmpresaController extends Controller
{
    // Metodo constructor
    public function __construct()
    {
        // Utiliza la autenticacion en toda la clase excepto en los metodos de index y show.
        $this->middleware('api.auth', ['except' => ['index', 'show', 'getImagen', 'destroyImagen']]);
    }

    // Metodo para actualizar los datos del calefon
    public function update($idEmpresa, Request $request)
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
                'titulo_header' => 'required',
                'direccion' => 'required',
                'correo_electronico' => 'required|numeric',
                'facebook' => 'required',
                'twitter' => 'required',
                'youtube' => 'required',
                'direccion' => 'required',
                'whatsapp' => 'required|numeric',
                'img_historia' => 'required|numeric',
                'descripcion_historia' => 'required|numeric',
                'mision' => 'required|numeric',
                'vision' => 'required|numeric',
                'latitud' => 'required|numeric',
                'longitud' => 'required|numeric',
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
                    $propiedades = Empresa::where('id', $idEmpresa)->update($paramsArray);
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
                        'message' => 'Error al actualizar los datos de la empresa',
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
            Storage::disk('empresa')->put($imageName, File::get($imagen)); // Guarda la imagen en el disco laravel

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
        $isset = Storage::disk('empresa')->exists($fileName);

        if ($isset) {
            $file = Storage::disk('empresa')->get($fileName); // Guarda el archivo encontrado.
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
            $isset = Storage::disk('empresa')->exists($nameImag);
            if ($isset) {
                $file = Storage::disk('empresa')->delete($nameImag); // Elimina el archivo encontrado.
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