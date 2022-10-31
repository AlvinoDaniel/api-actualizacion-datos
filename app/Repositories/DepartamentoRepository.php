<?php

namespace App\Repositories;

use App\Interfaces\DepartamentoRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\Departamento;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;


class DepartamentoRepository extends BaseRepository implements DepartamentoRepositoryInterface {

  /**
   * @var Model
   */
  protected $model;

  /**
   * Base Repository Construct
   *
   * @param Model $model
   */
  public function __construct(Departamento $departamento)
  {
      $this->model = $departamento;
  }

  /**
   * Listar todas las departamentos de un departamento
   */
  public function alldepartamentos(){
      $departamento = Auth::user()->personal->departamento_id;
      return Departamento::where('id','<>' ,$departamento)->get();
  }

}
