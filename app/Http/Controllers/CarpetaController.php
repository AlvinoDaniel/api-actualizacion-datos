<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Carpeta;
use App\Repositories\CarpetaRepository;
use App\Http\Requests\CarpetaRequest;

class CarpetaController extends AppBaseController
{
    private $repository;

    public function __construct(CarpetaRepository $carpetaRepository)
    {
        $this->repository = $carpetaRepository;
    }

     /**
     * Listar Carpetas de un Departamento.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $carpetas = $this->repository->getCarpetas();
            $message = 'Lista de Carpetas';
            return $this->sendResponse(['carpetas' => $carpetas], $message);
        } catch (\Throwable $th) {
            return $this->sendError("Ocurrio un error al intentar obtener el listado de carpetas del Departamento.");
        }
    }

     /**
     * Registrar carpeta de un Departamento.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CarpetaRequest $request)
    {
        $tipo_accion = 'Registrar carpeta';
        $data = [
            'nombre'            =>  $request->nombre,
            'departamento_id'   => $request->departamento_id
        ];
        try {
            $carpeta = $this->repository->createCarpeta($data);
            $this->generateLog(
                '200',
                'Se registro la carpeta: '.$carpeta->nombre,
                $tipo_accion,
                'success'
             );
            return $this->sendSuccess('Carpeta Registrada exitosamente.');
        } catch (\Throwable $th) {
            dd($th);
            $msg_error = $th->getMessage().' - CT: '.$th->getFile().' - LN: '.$th->getLine();
            $this->generateLog(
                $th->getCode(),
                $msg_error,
                $tipo_accion,
                'error'
             );
            return $this->sendError('Ocurrio un error al intentar registrar la carpeta');
        }
    }

    /**
     * Actualizar actividad artistica.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CarpetaRequest $request, $id)
    {

        try {      
            $this->repository->updateCarpeta($request->all(), $id)
            return $this->sendSuccess('Actividad Actualizada exitosamente.');
        } catch (\Throwable $th) {
            dd($th);
            $msg_error = $th->getMessage().' - CT: '.$th->getFile().' - LN: '.$th->getLine();
            $this->generateLog(
                $th->getCode(),
                $msg_error,
                $tipo_accion,
                'error'
             );
            return $this->sendError('Ocurrio un error al intentar actualizar la carpeta del departamento');
        }
    }

}
