<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Interfaces\GrupoRepositoryInterface;
use App\Http\Requests\GrupoRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class GrupoController extends AppBaseController
{
    private $repository;

    public function __construct(GrupoRepositoryInterface $grupoRepository)
    {
        $this->repository = $grupoRepository;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        try {
            $grupos = $this->repository->allGrupos();
            $message = 'Lista de Grupos';
            return $this->sendResponse(['grupos' => $grupos], $message);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GrupoRequest $request)
    {
        $data = [
            'nombre'            =>  $request->nombre,
            'descripcion'       =>  $request->descripcion,
            'departamento_id'   => Auth::user()->personal->departamento_id
        ];
        try {
            // DB::beginTransaction();
            $grupo = $this->repository->registrar($data);
            $this->repository->agregarDepartamentos($request->departamentos, $grupo->id);
            return $this->sendResponse(
                $grupo,
                'Grupo Registrado exitosamente.'
            );
            // DB::commit();
        } catch (\Throwable $th) {
            // DB::rollBack();
            return $this->sendError($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
