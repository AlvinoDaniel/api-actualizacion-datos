<?php

namespace App\Repositories;

use App\Interfaces\GrupoRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\Grupo;
use App\Models\GruposDepartamento;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class GrupoRepository extends BaseRepository implements GrupoRepositoryInterface {

  /**
   * @var Model
   */
  protected $model;

  /**
   * Base Repository Construct
   * 
   * @param Model $model
   */
  public function __construct(Grupo $grupo)
  {
      $this->model = $grupo;
  }

  /**
   * Listar todas las Grupos de un departamento
   */
  public function allGrupos(){
      $departamento = Auth::user()->personal->departamento_id;
      return Grupo::whereDepartamentoId($departamento)->with('departamentos')->get();
  }

  /**
   * Registrar grupo de un Departamento
   * @param Array $departamentos 
   * @param Integer $id_grupo 
   */
  public function registrarGrupo($data){
    
      foreach ($departamentos as $key => $dpto) {
         GruposDepartamento::create([
            'grupo_id'        => $id_grupo,
            'departamento_id' => $dpto['id'],
         ]);
      }
  }


        
}