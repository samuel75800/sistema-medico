<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use App\Models\Medico;
use App\Models\Paciente;
use App\Http\Requests\StoreConsultaRequest;
use App\Http\Requests\UpdateConsultaRequest;

class ConsultaController extends Controller
{
    public function index()
    {
        $consultas = Consulta::with(['medico', 'paciente'])
            ->when(request('status') && request('status') !== 'all', function ($query) {
                $query->where('status', request('status'));
            })
            ->when(request('q'), function ($query, $q) {
                $query->where(function ($query) use ($q) {
                    $query->whereHas('medico',   fn($q2) => $q2->where('nome', 'like', "%$q%"))
                          ->orWhereHas('paciente', fn($q2) => $q2->where('nome', 'like', "%$q%"))
                          ->orWhere('observacoes', 'like', "%$q%");
                });
            })
            ->orderBy('data', 'desc')
            ->orderBy('horario', 'desc')
            ->get();

        $medicos   = Medico::orderBy('nome')->get();
        $pacientes = Paciente::orderBy('nome')->get();

        return view('consultas.index', compact('consultas', 'medicos', 'pacientes'));
    }

    public function store(StoreConsultaRequest $request)
    {
        Consulta::create($request->validated());
        return back()->with('success', 'Consulta agendada com sucesso!');
    }

    public function update(UpdateConsultaRequest $request, Consulta $consulta)
    {
        $consulta->update($request->validated());
        return back()->with('success', 'Consulta atualizada com sucesso!');
    }

    public function destroy(Consulta $consulta)
    {
        $consulta->delete();
        return back()->with('success', 'Consulta removida com sucesso!');
    }
}