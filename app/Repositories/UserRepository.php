<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\User;
use App\Models\Personal;
use App\Models\Departamento;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Exception;


class UserRepository extends BaseRepository implements UserRepositoryInterface {

  /**
   * @var Model
   */
  protected $model;

  /**
   * Base Repository Construct
   * 
   * @param Model $model
   */
  public function __construct(User $user)
  {
      $this->model = $user;
  }

  /**
   * Registrar grupo de un Departamento
   * @param Array $departamentos 
   * @param Array $data 
   */
  public function verificarJefatura($personal_id){   

    try {
      $personal = Personal::find($personal_id);
  
      $usuariosJefe =  User::whereHas('personal' , function (Builder $query) use($personal) {
          $query->where('departamento_id', $personal->departamento_id);
        })
        ->whereHas('roles', function (Builder $query) {
          $query->where('name', 'jefe');
        })
        ->get();
       return $usuariosJefe->count() === 1 ? false : true;
    } catch (\Throwable $th) {
       throw new Exception('Hubo un error al intentar Registrar el Usuario.');
    }
 }
        
}