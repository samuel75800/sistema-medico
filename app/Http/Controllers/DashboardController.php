<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use App\Models\Paciente;
use App\Models\Consulta;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $total_medicos   = Medico::count();
        $total_pacientes = Paciente::count();
        $total_consultas = Consulta::count();
        $hoje_consultas  = Consulta::whereDate('data', Carbon::today())->count();

        $proximas = Consulta::with(['medico', 'paciente'])
            ->where('data', '>=', Carbon::today())
            ->where('status', 'agendada')
            ->orderBy('data')
            ->orderBy('horario')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'total_medicos',
            'total_pacientes',
            'total_consultas',
            'hoje_consultas',
            'proximas'
        ));
    }
}