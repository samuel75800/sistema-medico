<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMedicoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('medico')->id;

        return [
            'nome'          => ['required', 'string', 'max:100'],
            'email'         => ['required', 'email', 'unique:medicos,email,' . $id],
            'telefone'      => ['required', 'string', 'max:20'],
            'crm'           => ['required', 'string', 'max:20', 'unique:medicos,crm,' . $id],
            'especialidade' => ['required', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required'          => 'O nome é obrigatório.',
            'email.required'         => 'O e-mail é obrigatório.',
            'email.unique'           => 'Este e-mail já está em uso.',
            'telefone.required'      => 'O telefone é obrigatório.',
            'crm.required'           => 'O CRM é obrigatório.',
            'crm.unique'             => 'Este CRM já está em uso.',
            'especialidade.required' => 'A especialidade é obrigatória.',
        ];
    }
}