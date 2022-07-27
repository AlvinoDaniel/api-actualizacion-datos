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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
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
            'personal_id'  => [
                "required",
                "exists:personal,id",
            ],       
            // Rule::unique('users')->ignore($this->route('id'))
            'password'      => 'required|min:5',
            'status'        => 'nullable|boolean',
            'rol'           => 'required|exists:roles,name',
        ];
    }
}
