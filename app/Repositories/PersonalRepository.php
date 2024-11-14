<?php

namespace App\Repositories;

use App\Interfaces\PersonalRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\Personal;
use App\Models\PersonalMigracion;
use App\Models\PersonalUnidad;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;


class PersonalRepository extends BaseRepository {

  /**
   * @var Model
   */
  protected $model;

  /**
   * Base Repository Construct
   *
   * @param Model $model
   */
  public function __construct(Personal $personal)
  {
      $this->model = $personal;
  }

  /**
   * Listar todo el personal registrado de la unidad administrativa logueado
   */
  public function personalByUnidad(){
      $unidad_admin = Auth::user()->personal->codigo_unidad_admin;
      $unidad_ejec = Auth::user()->personal->codigo_unidad_ejec;
      try {
        $personal = Personal::where('codigo_unidad_admin', $unidad_admin)
            ->where('codigo_unidad_ejec', $unidad_ejec)
            ->where('cedula_identidad', '<>', Auth::user()->cedula)
            ->get();
        return $personal;
      } catch (\Throwable $th) {
        throw new Exception($th->getMessage());
      }
  }

  public function registrarPersonal($request){
    $departamento = PersonalUnidad::find($request['unidad']);
    $unidad_admin = $departamento->codigo_unidad_admin;
    $unidad_ejec = $departamento->codigo_unidad_ejec;
    $nucleo = Auth::user()->personal->cod_nucleo;
    $data = [
        'nombres_apellidos'   => $request[ 'nombres_apellidos'],
        'cedula_identidad'    => $request['cedula_identidad'],
        'tipo_personal'       => $request['tipo_personal'],       
        'cargo_opsu'          => $request['cargo_opsu'],
        'cod_nucleo'          => $nucleo,
        'correo'              => $request['correo'],
        'telefono'            => $request['telefono'],
        'pantalon'            => $request['pantalon'],
        'camisa'              => $request['camisa'],
        'zapato'              => $request['zapato'],
        'sexo'                => $request['sexo'],
        'area_trabajo'        => $request['area_trabajo'],
        'tipo_calzado'        => $request['tipo_calzado'],
        'prenda_extra'        => $request['prenda_extra'],
    ];
    try {
      DB::beginTransaction();
        $personal = Personal::create($data);
        $personal->unidades()->create([
          'codigo_unidad_admin' => $unidad_admin,
          'codigo_unidad_ejec'  => $unidad_ejec,
        ]);
      DB::commit();
      return $personal;
    } catch (\Throwable $th) {
      DB::rollBack();
      throw new Exception($th->getMessage());
    }
}

  public function searchPersonal($cedula) {
    $search = PersonalMigracion::where('cedula_identidad', 'like', '%'. $cedula. '%')->get();

    return $search;
  }

}
