<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Http\Requests\StorePacienteRequest;
use App\Http\Requests\UpdatePacienteRequest;

class PacienteController extends Controller
{
    public function index()
    {
        $pacientes = Paciente::withCount('consultas')
            ->when(request('q'), function ($query, $q) {
                $query->where(function ($query) use ($q) {
                    $query->where('nome', 'like', "%$q%")
                          ->orWhere('email', 'like', "%$q%")
                          ->orWhere('telefone', 'like', "%$q%");
                });
            })
            ->orderBy('nome')
            ->get();

        return view('pacientes.index', compact('pacientes'));
    }

    public function store(StorePacienteRequest $request)
    {
        Paciente::create($request->validated());
        return back()->with('success', 'Paciente cadastrado com sucesso!');
    }

    public function update(UpdatePacienteRequest $request, Paciente $paciente)
    {
        $paciente->update($request->validated());
        return back()->with('success', 'Paciente atualizado com sucesso!');
    }

    public function destroy(Paciente $paciente)
    {
        $paciente->delete();
        return back()->with('success', 'Paciente removido com sucesso!');
    }
}