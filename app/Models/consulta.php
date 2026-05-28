<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consulta extends Model
{
    protected $fillable = ['medico_id', 'paciente_id', 'data', 'horario', 'status', 'observacoes'];

    protected $casts = ['data' => 'date'];

    public function medico()
    {
        return $this->belongsTo(Medico::class);
    }

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }
}