<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Repositories\PersonalRepository;
use App\Http\Requests\PersonalRequest;
use Carbon\Carbon;

class PersonalController extends AppBaseController
{
    private $repository;

    public function __construct(PersonalRepository $personalRepository)
    {
        $this->repository = $personalRepository;
        $this->middleware('verifiedBoss');
    }

    /**
     * Listar todo el Personal.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $personal = $this->repository->personalByUnidad($request);
            $message = 'Lista de Trabajadores';
            return $this->sendResponse(['personal' => $personal], $message);
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
    public function store(PersonalRequest $request)
    {
        $data = $request->all();
        try {
            $personal = $this->repository->registrarPersonal($data);
            return $this->sendResponse(
                $personal,
                'Personal Registrado exitosamente.'
            );
        } catch (\Throwable $th) {
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
    public function update(PersonalRequest $request, $id)
    {
        $data = $request->all();
        try {
            $personal = $this->repository->actualizar($data, $id);
            return $this->sendResponse(
                $personal,
                'Personal Actualzado exitosamente.'
            );
        } catch (\Throwable $th) {
            return $this->sendError(
                $th->getCode() > 0
                    ? $th->getMessage()
                    : 'Hubo un error al intentar Actualizar el Personal'
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->repository->deletePersonal($id);
            return $this->sendSuccess(
                'Personal Eliminado Exitosamente.'
            );
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    public function search(Request $request) {
        $cedula = $request->cedula;

        try {
            $result = $this->repository->searchPersonal($cedula);
            return $this->sendResponse(
                $result,
                'Resultado de la Busqueda.'
            );
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    public function genareteList(Request $request){
        try {
            $personal = $this->repository->personalByUnidad($request);
            $unidad = $this->repository->getUnidad($request);

            if(!$unidad){
                return $this->sendError('Unidad Administrativa no existe.');
            }

            $pdf = \PDF::loadView('pdf.personal_registrado', [
               'personal'           => $personal,
                'unidad_admin'      => $unidad->descripcion_unidad_admin ?? '',
                'unidad_ejec'       => $unidad->descripcion_unidad_ejec ?? '',
                'nucleo'            => $unidad->nucleo->nombre ?? '',
                'fecha'             => Carbon::now()->format('d/m/Y'),
            ]);
            return $pdf->download('Personal_registrado.pdf');
        } catch (\Throwable $th) {
            return $this->sendError(
                $th->getCode() > 0
                    ? $th->getMessage()
                    : 'Hubo un error al intentar Obtener el documento'
            );
        }
    }
    public function genareteReport(Request $request){
        try {
            $personal = $this->repository->personalRegistrado($request);

            $pdf = \PDF::loadView('pdf.personal_by_nucleo', [
               'personal'           => $personal,
                'fecha'             => Carbon::now()->format('d/m/Y'),
            ]);
            return $pdf->download('Personal_by_nucleo.pdf');
        } catch (\Throwable $th) {
            return $this->sendError(
                $th->getCode() > 0
                    ? $th->getMessage()
                    : 'Hubo un error al intentar Obtener el documento'
            );
        }
    }
}
