<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
            'rol.*.exists' => 'Uno de los Roles seleccionado no existe.',
            'rol.between' => 'Solo puede asignar máximo 2 roles por usuario.',
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
            'password'      => 'required|min:5',
            'status'        => 'nullable|boolean',
            'rol'           => 'required|array|between:1,2',
            'rol.*'         => 'exists:roles,name',
            'cedula_identidad'  => [
                "required",
                "numeric",
                Rule::unique('personal')->ignore($this->route('id'))
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
