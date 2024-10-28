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
        ];
    }

}
