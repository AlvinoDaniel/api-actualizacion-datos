<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentoExternoRequest extends FormRequest
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
            'contenido'             => "required",
            'remitente'             => "required|string",
            'documento_remitente'   => "required|string",
            'email_remitente'       => "required|email",
            'telefono_remitente'    => "nullable|string",
            'nro_doc'               => "nullable|string",
            'responder'             => "required|boolean",
            'fecha_emision'         => "required|date",
        ];
    }
}
