<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Models\User;
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
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('role:administrador');
    }

    /**
     * Listar Usuarios
     *
     * [Se retorna la lista de los usuarios registrados.]
     *
     *
    */
    public function index(){
        $tipo_accion = 'Listar Usuarios';
        $user_logueado = Auth::user();
        try {
            $users = User::with('roles:id,name')->where('id', '!=', $user_logueado->id)->get();
            $message = 'Lista de Usuarios.';
            return $this->sendResponse(['users' => new UserCollection($users)], $message);
        } catch (\Throwable $th) {
            $msg_error = $th->getMessage().' - CT: '.$th->getFile().' - LN: '.$th->getLine();
            $this->generateLog(
                $th->getCode(),
                $msg_error,
                $tipo_accion,
                'error'
             );
            return $this->sendError('Ocurrio un error al intentar obtener el listado de Usuarios.');
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
        $tipo_accion = 'Registro de Usuario';
        $all_roles_in_database = Role::all()->pluck('name');
        $rol = $request->rol;
        $HAS_ROL = in_array($rol, $all_roles_in_database->toArray());
        $data = [
            'password'  =>  $request->password,
            'name'      =>  $request->name,
            'email'     =>  $request->email,
            'username'  =>  $request->username,
            'apellido'  =>  $request->apellido
        ];

        if(!$HAS_ROL) {
            return $this->sendError('El rol que desea asignar al usuario no existe.');
        }        

        try {
            $user = User::create($data);
            $user->assignRole($rol);
            $this->generateLog(
                '200',
                'Se registro el usuario: '.$user->email,
                $tipo_accion,
                'success'
             );
            return $this->sendSuccess('Usuario Registrado exitosamente.');
        } catch (\Throwable $th) {
            $msg_error = $th->getMessage().' - CT: '.$th->getFile().' - LN: '.$th->getLine();
            $this->generateLog(
                $th->getCode(),
                $msg_error,
                $tipo_accion,
                'error'
             );
            return $this->sendError('Ocurrio un error al intentar registrar el usuario');
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

    public function update(UpdateUserRequest $request,$id){
        $tipo_accion = 'Actualizar Usuario';
        $rol = $request->rol;
        $ITEMS_UPDATE = $request->except('rol');
        
        $all_roles_in_database = Role::all()->pluck('name');
        $HAS_ROL = in_array($rol, $all_roles_in_database->toArray());
        if(!$HAS_ROL) {
            return $this->sendError('El rol que desea asignar al usuario no existe.');
        }     

        try {
            $user = User::find($id);

            if(!$user){
                return $this->sendError('El usuario que desea actualizar no existe.');
            }

            $validator = Validator::make($ITEMS_UPDATE, [
                'email' => [Rule::unique('users')->ignore($user)],
                'username' => [Rule::unique('users')->ignore($user)],
            ]);

            if ($validator->fails()) {            
                return $this->sendError($validator->errors(), 422);
            }

            foreach ($ITEMS_UPDATE as $campo => $value) {
                $user->update([$campo => $value]);
            }
            $user->syncRoles($rol);
            $this->generateLog(
                '200',
                'Se actualizo el usuario: '.$user->email,
                $tipo_accion,
                'success'
             );
            return $this->sendSuccess('Usuario Actualizado exitosamente.');
        } catch (\Throwable $th) {
            $msg_error = $th->getMessage().' - CT: '.$th->getFile().' - LN: '.$th->getLine();
            $this->generateLog(
                $th->getCode(),
                $msg_error,
                $tipo_accion,
                'error'
             );
            return $this->sendError('Ocurrio un error al intentar actualizar el usuario');
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
        $tipo_accion = 'Eliminar Usuario'; 

        try {
            $user = User::find($id);

            if(!$user){
                return $this->sendError('El usuario que desea eliminar no existe.');
            }

            $user->syncRoles([]);
            $user->delete();
            return $this->sendSuccess('Usuario Eliminado exitosamente.');

        } catch (\Throwable $th) {
            $msg_error = $th->getMessage().' - CT: '.$th->getFile().' - LN: '.$th->getLine();
             $this->generateLog(
                $th->getCode(),
                $msg_error,
                $tipo_accion,
                'error'
             );
            return $this->sendError('Ocurrio un error al intentar eliminar el usuario');
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

