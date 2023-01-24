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
    public function messages()
    {
        return [
            'cedula_identidad.unique' => 'El personal seleccionado ya tiene un usuario registrado.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // dd(Rule::unique('users', 'personal_id')->ignore($this->route('id')));
        return [
            'email'  => [
                "required",
                "email",
                Rule::unique('users')->ignore($this->route('id'))
            ],
            'usuario'  => [
                "required",
                Rule::unique('users')->ignore($this->route('id'))
            ],
            'status'        => 'nullable|boolean',
            'rol'           => 'required|exists:roles,name',
            'cedula_identidad'  => [
                "required",
                "numeric",
                Rule::unique('personal')->ignore($this->route('personal'))
            ],
            'departamento_id'   => [
                "required",
                "exists:departamentos,id",
            ],
            'nombres_apellidos'   => "required",
            'cargo'     => "required",
            'nucleo'    => "required",
            'correo'    => "nullable|email",
            'firma'     => "nullable|image",
        ];
    }

}
