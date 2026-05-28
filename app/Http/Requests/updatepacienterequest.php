<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePacienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('paciente')->id;

        return [
            'nome'           => ['required', 'string', 'max:100'],
            'email'          => ['required', 'email', 'unique:pacientes,email,' . $id],
            'telefone'       => ['required', 'string', 'max:20'],
            'nascimento'     => ['nullable', 'date'],
            'tipo_sanguineo' => ['nullable', 'string', 'max:5'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required'     => 'O nome é obrigatório.',
            'email.required'    => 'O e-mail é obrigatório.',
            'email.unique'      => 'Este e-mail já está em uso.',
            'telefone.required' => 'O telefone é obrigatório.',
            'nascimento.date'   => 'Data de nascimento inválida.',
        ];
    }
}