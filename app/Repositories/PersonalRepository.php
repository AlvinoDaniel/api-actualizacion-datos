<?php

namespace App\Repositories;

use App\Interfaces\PersonalRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\Personal;
use App\Models\PersonalMigracion;
use App\Models\PersonalUnidad;
use App\Models\Unidad;
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
  public function personalByUnidad($request){
      $jefe = Auth::user()->personal;

      if(!$request->admin || !$request->ejec){
        return [];
      }

      try {
        $personal = DB::table('personal')->select('personal.*', 'tipo_personal.descripcion as tipo_personal_descripcion', 'nucleo.nombre as nucleo_nombre', 'personal_unidades.codigo_unidad_admin', 'personal_unidades.codigo_unidad_ejec')
            ->where('personal.cedula_identidad', '<>', Auth::user()->cedula)
            ->where('personal.cod_nucleo', $jefe->cod_nucleo)
            ->join('personal_unidades', function ($join) use($request, $jefe) {
                $join->on('personal.cedula_identidad', '=', 'personal_unidades.cedula_identidad')
                ->where('personal_unidades.codigo_unidad_admin', $request->admin)
                ->where('personal_unidades.codigo_unidad_ejec', $request->ejec);
            })
            ->leftJoin('tipo_personal', 'personal.tipo_personal', '=', 'tipo_personal.id')
            ->leftJoin('nucleo', 'personal.cod_nucleo', '=', 'nucleo.codigo_concatenado')
            ->get();
        return $personal;
      } catch (\Throwable $th) {
        throw new Exception($th->getMessage());
      }
  }

  public function personalRegistered($request){

      if(!isset($request->admin) || !isset($request->ejec) || !isset($request->nucleo)){
        return [];
      }

      try {
        $personal = DB::table('personal')->select('personal.*', 'tipo_personal.descripcion as tipo_personal_descripcion', 'nucleo.nombre as nucleo_nombre', 'personal_unidades.codigo_unidad_admin', 'personal_unidades.codigo_unidad_ejec')
            ->where('personal.cod_nucleo', $request->nucleo)
            ->join('personal_unidades', function ($join) use($request) {
                $join->on('personal.cedula_identidad', '=', 'personal_unidades.cedula_identidad')
                ->where('personal_unidades.codigo_unidad_admin', $request->admin)
                ->where('personal_unidades.codigo_unidad_ejec', $request->ejec);
            })
            ->leftJoin('tipo_personal', 'personal.tipo_personal', '=', 'tipo_personal.id')
            ->leftJoin('nucleo', 'personal.cod_nucleo', '=', 'nucleo.codigo_concatenado')
            ->get();

        $jefe = $personal->where('jefe', 1)->first();
        $personal_all = [];

        foreach ($personal as $item) {
            if($item->jefe === 0){
                $personal_all[] = $item;
            }
        }

        return [
            "jefe"      => $jefe,
            "personal"  => $personal_all,
        ];
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
    $personal = Personal::where('cedula_identidad', $cedula)->get();

    if($personal->count() > 0){
        throw new Exception('El Trabajador ya está registrado.', 422);
    }

    $search = PersonalMigracion::where('cedula_identidad', 'like', '%'. $cedula. '%')->first();

    return $search;
  }

  public function deletePersonal($id){
    $personal = Personal::where('id', $id)->first();

    if(!$personal){
        throw new Exception('El Personal que desea Eliminar no existe', 422);
    }

    try {
        PersonalUnidad::where('cedula_identidad', $personal->cedula_identidad)->delete();
        return $personal->delete();
      } catch (\Throwable $th) {
        throw new Exception($th->getMessage());
      }


  }

  public function getUnidad($request){
    try {
        if(!$request->admin || !$request->ejec){
            return null;
        }

        $unidad = Unidad::with(['nucleo'])
            ->where('codigo_unidad_admin', $request->admin)
            ->where('codigo_unidad_ejec', $request->ejec)
            ->first();

        if(!$unidad){
            return null;
        }

        return $unidad;
    } catch (\Throwable $th) {
        throw new Exception($th->getMessage());
    }
  }

   /**
   * Listar todo el personal registrado de la unidad administrativa logueado
   */
  public function personalRegistrado($request){
    $perPage = isset($request->perPage) ? $request->perPage : 10;
    try {
    $unidades = DB::table('unidades_fisicas_ejecutoras')
                ->select('codigo_unidad_admin', 'codigo_unidad_ejec', 'descripcion_unidad_admin')
                ->distinct('codigo_unidad_admin');

      $personal = DB::table('personal')->select('unidades_fisicas_ejecutoras.descripcion_unidad_admin', 'unidades_fisicas_ejecutoras.codigo_unidad_admin', 'unidades_fisicas_ejecutoras.codigo_unidad_ejec','nucleo.nombre', 'personal.cod_nucleo', DB::raw('count(personal.id) as personal_reg'))
          ->where('personal.jefe', 0)
          ->whereNotNull('personal.created_at')
          ->join('personal_unidades', function ($join) use($unidades){
              $join->on('personal.cedula_identidad', '=', 'personal_unidades.cedula_identidad')
              ->joinSub($unidades, 'unidades_fisicas_ejecutoras', function ($join){
                $join->on('personal_unidades.codigo_unidad_admin', '=', 'unidades_fisicas_ejecutoras.codigo_unidad_admin')
                ->whereColumn('unidades_fisicas_ejecutoras.codigo_unidad_ejec', 'personal_unidades.codigo_unidad_ejec');
              });
          })
          ->leftJoin('nucleo', 'personal.cod_nucleo', '=', 'nucleo.codigo_concatenado')
          ->groupBy('unidades_fisicas_ejecutoras.descripcion_unidad_admin', 'nucleo.nombre', 'unidades_fisicas_ejecutoras.codigo_unidad_admin', 'unidades_fisicas_ejecutoras.codigo_unidad_ejec', 'personal.cod_nucleo');

      if(isset($request->nucleo)){
        $personal->where('personal.cod_nucleo', $request["nucleo"]);
      }
      $data = $personal->get();
      return $data;
    } catch (\Throwable $th) {
      throw new Exception($th->getMessage());
    }
}

}
