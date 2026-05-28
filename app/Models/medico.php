<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medico extends Model
{
    protected $fillable = ['nome', 'email', 'telefone', 'crm', 'especialidade'];

    public function consultas()
    {
        return $this->hasMany(Consulta::class);
    }
}