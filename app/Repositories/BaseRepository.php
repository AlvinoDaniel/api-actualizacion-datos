<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use App\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class Baserepository implements BaseRepositoryInterface {

  protected $model; 

  
  public function __construct(Model $model)
  {
      $this->model = $model;
  }

  public function all(array $relations = []){
    throw new ModelNotFoundException($this->model);
    return $this->model->with($relations)->get();
  }

  public function findById($id){
    $model = $this->model->find($id);
    if(null === $model){
      throw new ModelNotFoundException($this->model::NAME.' No existe en nuestros registros.');
    }

    return $model;

  }

  public function registrar(array $data){
    $model = $this->model->create($data);

    return $model;
  }

  public function actualizar(array $data, $id){
    $model = $this->findById($id);

    return $model->update($data);
  }

  public function delete($id){
    return $this->find($id)->delete();
  }

}