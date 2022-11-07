<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\DocumentoRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Documento;
use App\Models\DocumentosDepartamento;
use App\Repositories\DocumentoRepository;
use App\Traits\DepartamentoTrait;
use Carbon\Carbon;

use Exception;

class DocumentoController extends AppBaseController
{
    use DepartamentoTrait;

    private $repository;

    public function __construct(DocumentoRepository $documentoRepository)
    {
        $this->repository = $documentoRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DocumentoRequest $request)
    {
        $data = [
            'asunto'            =>  $request->asunto,
            'contenido'         =>  $request->contenido,
            'tipo_documento'    =>  $request->tipo_documento,
            'estatus'           =>  Documento::ESTATUS_ENVIADO,
            'departamento_id'   =>  Auth::user()->personal->departamento_id,
            'fecha_enviado'     =>  Carbon::now(),
            'copia'             =>  $request->copias
        ];
        $hasCopia = $request->copias;
        $departamentos_destino = explode(',',trim($request->departamentos_destino));
        $departamentos_copias = $hasCopia ? explode(',',trim($request->departamentos_copias)) : [];
        $dataCopia = [
            'copia'         => $request->copias,
            'departamentos' => $departamentos_copias
        ];
        try {
            $this->validarDepartamentos($departamentos_destino);
            if($request->copias){
                $this->validarDepartamentos($departamentos_copias);
            }
            $documento = $this->repository->crearDocumento($data, $departamentos_destino, $dataCopia);
            return $this->sendResponse(
                $documento,
                'Documento enviado exitosamente'
            );
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
    public function storeTemporal(DocumentoRequest $request)
    {
        $data = [
            'asunto'            =>  $request->asunto,
            'nro_documento'     =>  0,
            'contenido'         =>  $request->contenido,
            'tipo_documento'    =>  $request->tipo_documento,
            'departamento_id'   =>  Auth::user()->personal->departamento_id,
        ];
        $data['estatus'] = $request->estatus === 'corregir' ? Documento::ESTATUS_POR_CORREGIR : Documento::ESTATUS_BORRADOR;

        $hasCopia = $request->copias;
        $departamentos_destino = explode(',',trim($request->departamentos_destino));
        $departamentos_copias = $hasCopia ? explode(',',trim($request->departamentos_copias)) : [];
        $dataTemporal = [
            'departamentos_destino' => $request->departamentos_destino,
            'departamentos_copias'  => $request->departamentos_copias,
            'tieneCopia'            => $hasCopia,
        ];
        try {
            $this->validarDepartamentos($departamentos_destino);
            if($request->copias){
                $this->validarDepartamentos($departamentos_copias);
            }
            $documento = $this->repository->crearTemporalDocumento($data, $dataTemporal);
            return $this->sendResponse(
                $documento,
                'Documento guardado exitosamente'
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
    public function show(Request $request, $id)
    {
        $relaciones = null;
        if (isset($request->estatus)) {
            switch ($request->estatus) {
                case 'temporal':
                    $relaciones = ['temporal'];
                    break;
                case 'enviado':
                    $relaciones = ['enviados', 'dptoCopias'];
                    break;

                default:
                    $relaciones = [];
                    break;
            }
        }
        try {
            $documento = $this->repository->obtenerDocumento($id, $relaciones);
            if($documento->departamento_id !== Auth::user()->personal->departamento_id){
                $this->repository->leidoDocumento($id);
            }
            return $this->sendResponse(
                $documento,
                $request->estatus
            );
        } catch (\Throwable $th) {
            return $this->sendError(
                $th->getMessage()
                // $th->getCode() > 0
                //     ? $th->getMessage()
                //     : 'Hubo un error al intentar Obtener el documento'
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DocumentoRequest $request, $id)
    {
        $status = [
            'enviar' => Documento::ESTATUS_ENVIADO,
            'borrador' => Documento::ESTATUS_BORRADOR,
            'corregir' => Documento::ESTATUS_POR_CORREGIR,
        ];
        $data = [
            'asunto'            =>  $request->asunto,
            'contenido'         =>  $request->contenido,
            'tipo_documento'    =>  $request->tipo_documento,
            'estatus'           =>  $status[$request->estatus],
            'departamento_id'   =>  Auth::user()->personal->departamento_id,
        ];
        $hasCopia = $request->copias;
        $departamentos_destino = explode(',',trim($request->departamentos_destino));
        $departamentos_copias = $hasCopia ? explode(',',trim($request->departamentos_copias)) : [];
        $dataTemporal = [
            'departamentos_destino' => $request->departamentos_destino,
            'departamentos_copias'  => $request->departamentos_copias,
            'tieneCopia'            => $hasCopia,
        ];
        try {
            $this->validarDepartamentos($departamentos_destino);
            if($hasCopia){
                $this->validarDepartamentos($departamentos_copias);
            }
            $documento = $this->repository->updateTemporalDocumento($data, $dataTemporal, $id);
            $mensaje = $request->estatus === Documento::ESTATUS_ENVIADO ? 'Documento enviado exitosamente' : 'Documento guardado exitosamente';
            return $this->sendResponse(
                $documento,
                $mensaje
            );
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
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
        //
    }
}
