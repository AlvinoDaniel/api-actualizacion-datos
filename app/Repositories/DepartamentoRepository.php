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
      $departamento = Auth::user()->personal->departamento;
      return Departamento::where('id','<>' ,$departamento->id)
        ->where('cod_nucleo',$departamento->cod_nucleo)
        ->get();

  }
  /**
   * Listar todas las departamentos de un Nucleo
   */
  public function departamentsByNucleo($nucleo){
      return Departamento::where('cod_nucleo', $nucleo)
        ->get();
  }

}
