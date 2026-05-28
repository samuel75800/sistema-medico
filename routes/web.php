<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\ConsultaController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('login'));

Route::middleware('guest')->group(function () {
    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/medicos',             [MedicoController::class, 'index'])->name('medicos.index');
    Route::post('/medicos',            [MedicoController::class, 'store'])->name('medicos.store');
    Route::put('/medicos/{medico}',    [MedicoController::class, 'update'])->name('medicos.update');
    Route::delete('/medicos/{medico}', [MedicoController::class, 'destroy'])->name('medicos.destroy');

    Route::get('/pacientes',               [PacienteController::class, 'index'])->name('pacientes.index');
    Route::post('/pacientes',              [PacienteController::class, 'store'])->name('pacientes.store');
    Route::put('/pacientes/{paciente}',    [PacienteController::class, 'update'])->name('pacientes.update');
    Route::delete('/pacientes/{paciente}', [PacienteController::class, 'destroy'])->name('pacientes.destroy');

    Route::get('/consultas',               [ConsultaController::class, 'index'])->name('consultas.index');
    Route::post('/consultas',              [ConsultaController::class, 'store'])->name('consultas.store');
    Route::put('/consultas/{consulta}',    [ConsultaController::class, 'update'])->name('consultas.update');
    Route::delete('/consultas/{consulta}', [ConsultaController::class, 'destroy'])->name('consultas.destroy');
});