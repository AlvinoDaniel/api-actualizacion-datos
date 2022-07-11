<?php

namespace App\Repositories;

// use App\Interfaces\OrderRepositoryInterface;
use App\Models\Carpeta;
use Illuminate\Support\Facades\DB;

class CarpetaRepository {

    public function getCarpetas()
    {
        return DB::table('carpetas')->get();
    }

    public function createCarpeta($carpetaDetails)
    {
        retunr Carpeta::create($carpetaDetails);
    }

    public function updateCarpeta($carpetaDetails, $id)
    {
        $carpeta = Carpeta::find($id);
        // if(!$carpeta){
        //     return $this->sendError('La carpeta que desea actualizar no existe.');
        // }
        foreach ($carpetaDetails as $campo => $value) {
            if(!empty($value)){
                $carpeta->update([$campo => $value]);
            }
        }
    }

    public function destroyCarpeta($id)
    {

        $carpeta = Carpeta::find($id);

        if(!$carpeta){
            return $this->sendError('La actividad que desea eliminar no existe.');
        }

        $carpeta->delete();
    }
}