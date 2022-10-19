<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\Documento;
use App\Models\DocumentosDepartamento;
use App\Models\DocumentosTemporal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

class DocumentoRepository {



    /**
     * @var Model
     */
    protected $model;

    /**
     * Base Repository Construct
     *
     * @param Model $model
     */
    public function __construct(Documento $documento)
    {
        $this->model = $documento;
    }

    /**
     * Crear Documento
     */
    public function crearDocumento($data, $destino, $dataCopias){
        $ultimo_registro = Documento::select('nro_documento')->orderBy('id')->get()->last();
        if($ultimo_registro){
            $id_nuevo = str_pad($ultimo_registro->nro_documento + 1, 4, '0', STR_PAD_LEFT);
        }
        else {
            $id_nuevo = str_pad('0', 4, '0', STR_PAD_LEFT);
        }

        $data['nro_documento'] = $id_nuevo;

        try {
            DB::beginTransaction();
            $documento = Documento::create($data);

            foreach ($destino as $dpto_destino) {
                DocumentosDepartamento::create([
                    'documento_id'        => $documento->id,
                    'departamento_id' => $dpto_destino,
                    'leido' => false,
                    'copia' => false,
                ]);
            }

            if($dataCopias['copia']){
                foreach ($dataCopias['departamentos'] as $dpto_copia) {
                    DocumentosDepartamento::create([
                        'documento_id'        => $documento->id,
                        'departamento_id' => $dpto_copia,
                        'leido' => false,
                        'copia' => true,
                    ]);
                }
            }

            DB::commit();
            return $documento;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new Exception('Hubo un error al intentar Enviar el documento.');
            //
        }
    }
    /**
     * Crear Documento temporal
     */
    public function crearTemporalDocumento($data, $dataTemporal){

        try {
            DB::beginTransaction();
            $documento = Documento::create($data);

            $dataTemporal['documento_id'] = $documento->id;

            $temporal = DocumentosTemporal::create($dataTemporal);

            DB::commit();
            return $documento;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new Exception($th->getMessage());
            // throw new Exception($th->getMessage());
        }
    }
    /**
     * Actualizar Documento temporal
     */
    public function updateTemporalDocumento($data, $dataTemporal, $id){
        $documento = Documento::find($id);
        if(!$documento){
            throw new Exception('El departamento con id '.$id.' no existe.');
            return;
        }
        try {
            DB::beginTransaction();
            foreach ($data as $campo => $value) {
                if(!empty($value)){
                    $documento->update([$campo => $value]);
                }
            }

            if($data['estatus'] === Documento::ESTATUS_ENVIADO){
                $departamentos_destino = explode(',',trim($dataTemporal['departamentos_destino']));
                $departamentos_copias = $dataTemporal['tieneCopia'] ? explode(',',trim($dataTemporal['departamentos_copias'])) : [];

                foreach ($departamentos_destino as $dpto_destino) {
                    DocumentosDepartamento::create([
                        'documento_id'        => $documento->id,
                        'departamento_id' => $dpto_destino,
                        'leido' => false,
                        'copia' => false,
                    ]);
                }

                if($dataTemporal['tieneCopia']){
                    foreach ($departamentos_copias as $dpto_copia) {
                        DocumentosDepartamento::create([
                            'documento_id'        => $documento->id,
                            'departamento_id' => $dpto_copia,
                            'leido' => false,
                            'copia' => true,
                        ]);
                    }
                }
                $temporal = DocumentosTemporal::where('documento_id', $id)->first();
                $temporal->delete();

            } else {
                foreach ($data as $campo => $value) {
                    if(!empty($value)){
                        $documento->update([$campo => $value]);
                    }
                }
                $dataTemporal['documento_id'] = $id;

                $temporal = DocumentosTemporal::where('documento_id', $id)->first();
                foreach ($dataTemporal as $campo => $value) {
                    if(!empty($value)){
                        $temporal->update([$campo => $value]);
                    }
                }
            }

            DB::commit();
            return $documento;
            } catch (\Throwable $th) {
            DB::rollBack();
            throw new Exception('Hubo un error al intentar Guardar el documento.');
            // throw new Exception($th->getMessage());
            }
    }

    /**
   * Obtener un grupo de un Departamento
   * @param Integer $id
   */
   public function obtenerDocumento($id){
    try {

       $documento = Documento::find($id);
       if(!$documento) {
          throw new Exception('El documento con id '.$id.' no existe.',422);
       }
       return $documento;
    } catch (\Throwable $th) {
      throw new Exception($th->getMessage(), $th->getCode());
    }
   }
        /**
     * Cambiar el estatus de Leido del documento
     * @param Integer $id
     */
    public function leidoDocumento($id){
        try {
            $documento = DocumentosDepartamento::where('documento_id',$id)->where('departamento_id', Auth::user()->personal->departamento_id)->first();
            if(!$documento) {
                throw new Exception('El documento con id '.$id.' no existe.',422);
            }
            if($documento->leido === 0){
                $documento->update(['leido' => true]);
            }
        } catch (\Throwable $th) {
        throw new Exception($th->getMessage(), $th->getCode());
        }
    }


}
