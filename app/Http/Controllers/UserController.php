<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Models\User;
use App\Models\Nivel;
use App\Repositories\UserRepository;
use Spatie\Permission\Models\Role;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Resources\UserCollection;
use Illuminate\Support\Facades\Storage;
use Artisan;
use Exception;


class UserController extends AppBaseController
{
    private $repository;

    public function __construct(UserRepository $userRepository)
    {
        $this->repository = $userRepository;
    }

    /**
     * Crear usuario
     *
     * [Se asigna el usuario a algún vendedor existente en el sistema.]
     *
     * @bodyParam  email email required Correo de usuario. Example: jose@gmail.com
     * @bodyParam  username required pseudonimo del usuario.
     * @bodyParam  password string required Contraseña de usuario.
     * @bodyParam  name string required Nombre del usuario.
     * @bodyParam  apellido string required Nombre del usuario.
     * @bodyParam  rol array required Nombre del rol que se desea asignar. Example: ["administrador","estandar"]
     *
     *
     *
    */

    public function store(UserRequest $request){
        $data = $request->all();
        try {
            $user = $this->repository->registrarUsuario($data);
            return $this->sendResponse(
                $user,
                'Usuario Registrado exitosamente.'
            );
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), $th->getCode() ?? 404);
        }
    }

    /**
     * Actualizar contraseña de usuario
     *
     * [Se actualiza la contrseña de un usuario.]
     *
     * @bodyParam  newpassword
     * @bodyParam  repassword
     *
     *
    */

    public function update_password(Request $request,$id){
        $tipo_accion = 'Actualizar Contraseña';

        try {
            $user = User::find($id);

            if(!$user){
                return $this->sendError('El rol que desea asignar al usuario no existe.');
            }

            $validator = Validator::make($request->all(), [
                'newpassword' => 'required|min:5',
            ]);

            if ($validator->fails()) {
                return $this->sendError($validator->errors(), 422);
            }

            $user->update(['password' => $request->newpassword]);
            return $this->sendSuccess('Contraseña Actualizada exitosamente.');
        } catch (\Throwable $th) {
            $msg_error = $th->getMessage().' - CT: '.$th->getFile().' - LN: '.$th->getLine();
             $this->generateLog(
                $th->getCode(),
                $msg_error,
                $tipo_accion,
                'error'
             );
            return $this->sendError('Ocurrio un error al intentar actualizar la contraseña del usuario');
        }
    }

    public function update(UserUpdateRequest $request){
        $data = $request->all();
        try {
            $personal = $this->repository->actualizarUsuario($data);
            return $this->sendResponse(
                $personal,
                'Usuario Actualizado exitosamente.'
            );
        } catch (\Throwable $th) {
            return $this->sendError('Hubo un error al intentar Actualizar el Personal');
        }
    }


    public function backupDownload(){
        try {
            Artisan::call('backup:run');
            $files = array_reverse(Storage::disk('backup')->files('cultores'));
            return Storage::disk('backup')->download($files[0]);
        } catch (\Throwable $th) {
            $msg_error = $th->getMessage().' - CT: '.$th->getFile().' - LN: '.$th->getLine();
            return $this->sendError('Ocurrio un error al intentar crear el Respaldo');
        }
    }

    public function searchWorker(Request $request){
        try {
            $personal = $this->repository->search($request['cedula']);
            return $this->sendResponse(['personal' => $personal], '');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), $th->getCode() ?? 404);
        }
    }
}

