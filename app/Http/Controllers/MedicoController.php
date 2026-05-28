<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use App\Http\Requests\StoreMedicoRequest;
use App\Http\Requests\UpdateMedicoRequest;

class MedicoController extends Controller
{
    public function index()
    {
        $medicos = Medico::withCount('consultas')
            ->when(request('q'), function ($query, $q) {
                $query->where(function ($query) use ($q) {
                    $query->where('nome', 'like', "%$q%")
                          ->orWhere('email', 'like', "%$q%")
                          ->orWhere('crm', 'like', "%$q%")
                          ->orWhere('especialidade', 'like', "%$q%");
                });
            })
            ->orderBy('nome')
            ->get();

        return view('medicos.index', compact('medicos'));
    }

    public function store(StoreMedicoRequest $request)
    {
        Medico::create($request->validated());
        return back()->with('success', 'Médico cadastrado com sucesso!');
    }

    public function update(UpdateMedicoRequest $request, Medico $medico)
    {
        $medico->update($request->validated());
        return back()->with('success', 'Médico atualizado com sucesso!');
    }

    public function destroy(Medico $medico)
    {
        $medico->delete();
        return back()->with('success', 'Médico removido com sucesso!');
    }
}