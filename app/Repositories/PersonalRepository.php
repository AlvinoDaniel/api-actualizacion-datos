<?php

namespace App\Repositories;

use App\Interfaces\PersonalRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\Personal;
use App\Models\PersonalMigracion;
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
    $unidad_admin = Auth::user()->personal->codigo_unidad_admin;
    $unidad_ejec = Auth::user()->personal->codigo_unidad_ejec;
    $nucleo = Auth::user()->personal->cod_nucleo;
    $data = [
        'nombres_apellidos' => $request[ 'nombres_apellidos'],
        'cedula_identidad' => $request['cedula_identidad'],
        'tipo_personal' => $request['tipo_personal'],
        'codigo_unidad_admin' => $unidad_admin,
        'codigo_unidad_ejec' => $unidad_ejec,
        'cargo_opsu' => $request['cargo_opsu'],
        'cod_nucleo' => $nucleo,
        'correo' => $request['correo'],
        'telefono' => $request['telefono'],
        'pantalon' => $request['pantalon'],
        'camisa' => $request['camisa'],
        'zapato' => $request['zapato'],
    ];
    try {
      $personal = Personal::create($data);
      return $personal;
    } catch (\Throwable $th) {
      throw new Exception($th->getMessage());
    }
}

  public function searchPersonal($cedula) {
    $search = PersonalMigracion::where('cedula_identidad', 'like', '%'. $cedula. '%')->get();

    return $search;
  }

}
