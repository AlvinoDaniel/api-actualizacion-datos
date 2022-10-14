<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Departamento;
use App\Models\Documento;
use App\Http\Requests\BandejaRequest;
use App\Http\Resources\BandejaRecibidosCollection;
use App\Http\Resources\BandejaEnviadosCollection;
use Exception;

class BandejaController extends AppBaseController
{
     /**
     * OBTENER DOCUMENTOS ENVIADOS AL DEPARTAMENTO LOGUEADO.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function enviados()
    {
        try {
            $departamento_user = Auth::user()->personal->departamento_id;
            $departamento = Departamento::with(['documentos' => function ($query) {
                $query->where('estatus', Documento::ESTATUS_ENVIADO);
            }])->find( $departamento_user);
            $message = 'Lista de Documentos';
            return $this->sendResponse(
                [
                    'documentos' => new BandejaEnviadosCollection($departamento->documentos),
                ],
                $message);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }
     /**
     * OBTENER DOCUMENTOS BORRADORES AL DEPARTAMENTO LOGUEADO.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function borradores()
    {
        try {
            $departamento_user = Auth::user()->personal->departamento_id;
            $departamento = Departamento::with(['documentos' => function ($query) {
                $query->where('estatus', Documento::ESTATUS_BORRADOR);
            }])->find( $departamento_user);
            $message = 'Lista de Documentos';
            return $this->sendResponse(
                [
                    'documentos' => $departamento->documentos
                ],
                $message);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }
     /**
     * OBTENER DOCUMENTOS POR CORREGIR AL DEPARTAMENTO LOGUEADO.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function corregir()
    {
        try {
            $departamento_user = Auth::user()->personal->departamento_id;
            $departamento = Departamento::with(['documentos' => function ($query) {
                $query->where('estatus', Documento::ESTATUS_POR_CORREGIR);
            }])->find( $departamento_user);
            $message = 'Lista de Documentos';
            return $this->sendResponse(
                [
                    'documentos' => $departamento->documentos
                ],
                $message);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }
     /**
     * OBTENER DOCUMENTOS RECIBIDOS AL DEPARTAMENTO LOGUEADO.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function recibidos()
    {
        try {
            $departamento_user = Auth::user()->personal->departamento_id;
            $departamento = Departamento::with('recibidos')->find( $departamento_user);
            $message = 'Lista de Documentos';
            return $this->sendResponse(
                [
                    'documentos' => new BandejaRecibidosCollection($departamento->recibidos)
                ],
                $message);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }
}