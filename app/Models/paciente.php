<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $fillable = ['nome', 'email', 'telefone', 'nascimento', 'tipo_sanguineo'];

    protected $casts = ['nascimento' => 'date'];

    public function consultas()
    {
        return $this->hasMany(Consulta::class);
    }
}