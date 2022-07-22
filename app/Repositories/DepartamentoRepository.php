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
      return departamento::whereDepartamentoId($departamento)->with('departamentos')->get();
  }

  /**
   * Registrar departamento de un Departamento
   * @param Array $departamentos 
   * @param Array $data 
   */
   public function registrardepartamento($data, $departamentos){   

      try {
         DB::beginTransaction();
         $departamento = departamento::create($data);

         foreach ($departamentos as $dpto) {
               departamentosDepartamento::create([
               'departamento_id'        => $departamento->id,
               'departamento_id' => $dpto,
            ]);
         }

         DB::commit();
         return $departamento;
      } catch (\Throwable $th) {
         DB::rollBack();
         throw new Exception('Hubo un error al intentar Crear el departamento.');
      }
   }

  /**
   * Obtener un departamento de un Departamento
   * @param Integer $id 
   */
   public function obtenerdepartamento($id){  
      try {
         $departamento = departamento::with('departamentos')->find($id);
         if(!$departamento) {
            throw new Exception('El departamento con id '.$id.' no existe.');
         }         
         return $departamento;
      } catch (\Throwable $th) {
         throw new Exception('Hubo un error al intentar obtener el departamento.');
      }
   }

  /**
   * Obtener un departamento de un Departamento
   * @param Array $dptos 
   */
   public function validarDepartamentos($dptos){  
      foreach ($dptos as $departamento_id) {
         $dpto = Departamento::find($departamento_id);
         if(!$dpto){
            throw new Exception('El departamento con id '.$departamento_id.' no existe.');
         }
     }
   }

   /**
   * Actualizar departamento de un Departamento
   * @param Array $departamentos 
   * @param Array $data 
   */
   public function actualizardepartamento($data, $departamentos, $id){  

      $departamento = departamento::find($id);
   
      try {
         DB::beginTransaction();
         
         foreach ($data as $campo => $value) {
            if(!empty($value)){
               $departamento->update([$campo => $value]);
            }
         }
         //Se elimina todos los departamentos integrantes del departamento
         departamentosDepartamento::where('departamento_id', $id)->delete();
         //Se Reasigna todos los departamentos a actualizar
         foreach ($departamentos as $dpto) {
               departamentosDepartamento::create([
               'departamento_id'        => $departamento->id,
               'departamento_id' => $dpto,
            ]);
         }

         DB::commit();
         return $departamento;
      } catch (\Throwable $th) {
         DB::rollBack();
         throw new Exception('Hubo un error al intentar Actualizar el departamento.');
      }
   }

   /**
   * Actualizar departamento de un Departamento
   * @param Array $departamentos 
   * @param Array $data 
   */
   public function eliminardepartamento($id){  

      $departamento = departamento::find($id);
      if(!$departamento) {
         throw new Exception('El departamento con id '.$id.' no existe.');
      } 
   
      try {
         DB::beginTransaction();
         //Se elimina todos los departamentos integrantes del departamento
         departamentosDepartamento::where('departamento_id', $id)->delete();
         //Se elimina el departamento
         departamento::whereId($id)->delete();

         DB::commit();
      } catch (\Throwable $th) {
         DB::rollBack();
         throw new Exception('Hubo un error al intentar Eliminar el departamento.');
      }
   }

   /**
   * Eliminar Departamento de un departamento
   * @param Integer $id_departamento 
   * @param Integer $id_dpto 
   */
   public function eliminarDepartamentodepartamento($id_departamento,$id_dpto){  

      $departamento = departamento::find($id_departamento);
      if(!$departamento) {
         throw new Exception('El departamento con id '.$id_dpto.' no existe.');
      } 
   
      try {
         $departamento->departamentos()->detach($id_dpto);
      } catch (\Throwable $th) {
         throw new Exception('Hubo un error al intentar Eliminar el departamento del departamento.');
      }
   }

  /**
   * Agregar Departamento de un departamento
   * @param Integer $id_departamento 
   * @param Integer $id_dpto 
   */
   public function agregarDepartamentodepartamento($id_departamento,$id_dpto){  

      $departamento = departamento::find($id_departamento);
      if(!$departamento) {
         throw new Exception('El departamento con id '.$id_dpto.' no existe.');
      } 
   
      try {
         $departamento->departamentos()->attach($id_dpto);
      } catch (\Throwable $th) {
         throw new Exception('Hubo un error al intentar Registrar el departamento del departamento.');
      }
   }
        
}