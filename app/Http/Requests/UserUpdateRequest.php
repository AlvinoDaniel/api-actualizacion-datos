<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'correo'                => "required|email",
            'telefono'              => "required",
            'codigo_unidad_admin'    => "required",
            'codigo_unidad_ejec'     => "required",
            'telefono'              => "required",
            'pantalon'              => "required",
            'camisa'                => "required",
            'zapato'                => "required",
            'sexo'                  => "required",
            'area_trabajo'          => "required|exist:area_trabajo,id",
            'tipo_calzado'          => "required|exist:tipo_calzado,id",
            'prenda_extra'          => "required|exist:prenda_extra,id",
        ];
    }

}
