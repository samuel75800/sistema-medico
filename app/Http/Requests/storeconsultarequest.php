<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConsultaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'medico_id'   => ['required', 'exists:medicos,id'],
            'paciente_id' => ['required', 'exists:pacientes,id'],
            'data'        => ['required', 'date'],
            'horario'     => ['required'],
            'status'      => ['required', 'in:agendada,concluida,cancelada'],
            'observacoes' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'medico_id.required'   => 'Selecione um médico.',
            'medico_id.exists'     => 'Médico inválido.',
            'paciente_id.required' => 'Selecione um paciente.',
            'paciente_id.exists'   => 'Paciente inválido.',
            'data.required'        => 'A data é obrigatória.',
            'horario.required'     => 'O horário é obrigatório.',
            'status.required'      => 'O status é obrigatório.',
            'status.in'            => 'Status inválido.',
        ];
    }
}