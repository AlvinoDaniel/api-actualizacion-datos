<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Resources\UserAuthCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class AuthController extends AppBaseController
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }


    /**
     * Login de usuario
     *
     * [Se envia email y contraseña para aceeder a la sesión.]
     *
     * @bodyParam  email email required Correo de usuario. Example: jose@gmail.com
     * @bodyParam  password string required Contraseña de usuario.
     *
     * @responseFile  responses/AuthController/login.post.json
     *
    */
   public function login(LoginRequest $request){
      $tipo_accion =  'Login';

      try {
         $user = User::where('email', $request->username_email)
                  ->orWhere('usuario', $request->username_email)
                  ->first();
         if(!$user){
            return $this->sendError('El Email/Usuario no existe en nuestros registros.');
         }

        //  if (!Hash::check($request->password, $user->password)) {
        //     return $this->sendError('Las credenciales no concuerdan. Email o Contraseña inválida');
        //  }

         $token = $user->createToken('TokenCultorApi-'.$user->name)->plainTextToken;
         $message = 'Usuario Autenticado exitosamente.';

         // $this->generateLog(
         //    '200',
         //    $message,
         //    $tipo_accion,
         //    'success'
         // );
         return $this->sendResponse(['token' => $token ], $message);

      } catch (\Throwable $th) {
         // $this->generateLog(
         //    $th->getCode(),
         //    $th->getMessage(),
         //    $tipo_accion,
         //    'error'
         // );
         return $this->sendError('Ocurrio un error, contacte al administrador');
      }
   }

   /**
     * Obtener información de usuario logeado.
     *
     * [Petición para obtener informacion del usuario logeado..]
     *
     * @responseFile  responses/AuthController/me.get.json
     *
    */
   public function me()
   {
      $user = Auth::user()->load(['personal', 'roles']);
      // $user = Auth::user()->personal->departamento_id;
      return $this->sendResponse([ 'user' => new UserAuthCollection([$user]) ], 'Datos de Usuario Logeado');
      // return $this->sendResponse([ 'user' => $user ], 'Datos de Usuario Logeado');
   }

     /**
     * Cerrar sesión.
     *
     * [Petición para cerrar sesión.]
     *
     * @response
        {
            "message": "Sesión cerrada con exito."
        }
     *
    */

    public function logout(){
      $user = Auth::user();
      try {
          //Auth::user()->currentAccessToken()->delete();
         $user->tokens()->delete();
         return $this->sendSuccess('Sesión cerrada con exito.');
      } catch (\Throwable $th) {
         return $this->sendError('Ocurrio un error al intentar cerrar la sesion.', 422);
      }
  }

   /**
     * Cambiar clave
     *
     * [Cambiar contraseña de usuario.]
     *
     * @bodyParam  password string required Contraseña actual de usuario.
     * @bodyParam  newpassword string required Nueva contraseña de usuario.
     * @bodyParam  repassword string required Nueva contraseña repetida de usuario.
     *
     * @response {
            "message": "Se actualizo la contraseña"
        }
     *
     *
    */

   public  function changePassword(ChangePasswordRequest $request)
   {
      $user = Auth::user();
      $password = Hash::make($request->password);
      if(Hash::check($request->password, $user->password)) {
         try {
            $user->update(['password'=>$request->newpassword]);
            return $this->sendSuccess('Se actualizo su contraseña Exitosamente');
         } catch (\Throwable $th) {
            return $this->sendError('Lo sentimos, hubo un error al intentar regustrar su nueva contraseña.', 422);
         }
      } else {
         return $this->sendError('La contraseña actual no coincide con nuestros registros.', 422);
      }
   }
}
