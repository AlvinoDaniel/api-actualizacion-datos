<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PersonalRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cedula_identidad'  => [
                "required",
                "numeric",
                "min:5",
                Rule::unique('personal')->ignore($this->route('id'))
            ],
            'nombres_apellidos'     => "required",
            'cargo_opsu'            => "required",
            'correo'                => "required|email",
            'telefono'              => "required",
            'tipo_personal'         => "required",
            'telefono'              => "required",
            'pantalon'              => "required",
            'camisa'                => "required",
            'zapato'                => "required",
        ];
    }

    public function messages()
    {
        return [
            'cedula_identidad.unique' => 'El Funcionario seleccionado ya está registrado.',
        ];
    }
}
