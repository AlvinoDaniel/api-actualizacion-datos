<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\DocumentoExternoRequest;
use App\Repositories\DocumentoExternoRepository;
use App\Http\Controllers\AppBaseController;
use App\Models\DocumentoExterno;

class DocumentoExternoController extends AppBaseController
{
    private $repository;

    public function __construct(DocumentoExternoRepository $documentoRepository)
    {
        $this->repository = $documentoRepository;
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DocumentoExternoRequest $request)
    {
        try {
            $documento = $this->repository->crearDocumento($request);
            return $this->sendResponse(
                $documento,
                'Documento Externo enviado exitosamente'
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
        try {
            $documento = DocumentoExterno::find($id);
            return $this->sendResponse(
                $documento,
                'Documento Externo'
            );
        } catch (\Throwable $th) {
            return $this->sendError(
                $th->getCode() > 0
                    ? $th->getMessage()
                    : 'Hubo un error al intentar Obtener el documento'
            );
        }
    }

}
