<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Models\User;
use App\Interfaces\UserRepositoryInterface;
use Spatie\Permission\Models\Role;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Resources\UserCollection;
use Illuminate\Support\Facades\Storage;
use Artisan;


class UserController extends AppBaseController
{
    private $repository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->middleware('auth:api');
        $this->repository = $userRepository;
    }

    /**
     * Listar Usuarios
     *
     * [Se retorna la lista de los usuarios registrados.]
     *
     *
    */
    public function index(){
        try {
            $usuarios = $this->repository->all(['personal']);
            $message = 'Lista de Usuarios';
            return $this->sendResponse(['usuarios' => $usuarios], $message);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
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
        $data = $request->except(['status', 'rol']);
        $data['status'] = empty($request->status) ? 0 : $request->status;
        $rol = $request->rol;
        try {
            $user = $this->repository->registrar($data);
            $user->assignRole($rol);
            return $this->sendResponse(
                $user,
                'Usuario Registrado exitosamente.'
            );
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
            // 'Hubo un error al intentar Registrar el Usuario'
        }
    }

    /**
     * Actualizar usuario
     *
     * [Se actualiza la infomacion de un usuario.]
     *
     * @bodyParam  email email Correo de usuario. Example: jose@gmail.com
     * @bodyParam  username pseudonimo del usuario. 
     * @bodyParam  name string Nombre del usuario.
     * @bodyParam  apellido string Nombre del usuario.
     * @bodyParam  rol array Nombre del rol que se desea asignar. Example: ["administrador","estandar"]
     *
     *
    */

    public function update(UserRequest $request,$id){
        
        $data = $request->except(['status', 'rol']);
        $data['status'] = empty($request->status) ? 0 : $request->status;
        $rol = $request->rol;
        try {
            $user = $this->repository->actualizar($data, $id);
            $user->syncRoles($rol);
                return $this->sendResponse(
                    $user,
                    'Usuario Actualzado exitosamente.'
                );
            } catch (\Throwable $th) {
                return $this->sendError(
                    $th->getCode() > 0 
                        ? $th->getMessage() 
                        : 'Hubo un error al intentar Actualizar el Usuario'
                );
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

     /**
     * Eliminar usuario
     *
     * [Se Elemina un usuario.]
     *
     *
     *
    */

    public function delete($id){
        try {
            $user = $this->repository->findById($id);
            $user->syncRoles([]);
            $this->repository->delete($id);
            return $this->sendSuccess(
                'Usuario Eliminado Exitosamente.'
            );
        } catch (\Throwable $th) {
            return $this->sendError(
                $th->getCode() > 0 
                    ? $th->getMessage() 
                    : 'Hubo un error al intentar Eliminar el Usuario'
            );
        }
    }

    public function roles(){
        $tipo_accion = 'Listar Roles';
        try {
            $all_roles = Role::select('id','name')->get();
            $message = 'Lista de Roles.';
            return $this->sendResponse(['roles' => $all_roles], $message);
        } catch (\Throwable $th) {
            $msg_error = $th->getMessage().' - CT: '.$th->getFile().' - LN: '.$th->getLine();
            $this->generateLog(
                $th->getCode(),
                $msg_error,
                $tipo_accion,
                'error'
             );
            return $this->sendError('Ocurrio un error al intentar obtener el listado de roles');
        }
    }
    public function backupDownload(){
        try {
            Artisan::call('backup:run');
            $files = array_reverse(Storage::disk('backup')->files('cultores'));
            return Storage::disk('backup')->download($files[0]);            
        } catch (\Throwable $th) {
            $msg_error = $th->getMessage().' - CT: '.$th->getFile().' - LN: '.$th->getLine();
            $this->generateLog(
               $th->getCode(),
               $msg_error,
               $tipo_accion,
               'error'
            );
            return $this->sendError('Ocurrio un error al intentar crear el Respaldo');
        }
    }  
}

