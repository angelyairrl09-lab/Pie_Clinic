<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiagnosticoController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\TratamientoController;
use App\Http\Controllers\ExpedienteController;
use App\Http\Controllers\ConsultaController;

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware(['auth', 'verified'])->group(function () {

    Route::patch(
        '/pacientes/{paciente}/estado',
        [PacienteController::class, 'cambiarEstado']
    )->name('pacientes.estado');

    Route::resource(
        'pacientes',
        PacienteController::class
    )->except(['destroy']);

     Route::get(
        '/diagnosticos/{diagnostico}/imagenes/{imagen}',
        [DiagnosticoController::class, 'imagen']
    )->name('diagnosticos.imagen');

    Route::resource(
        'diagnosticos',
        DiagnosticoController::class
    )->except(['destroy']);

    Route::patch(
    '/citas/{cita}/estado',
    [CitaController::class, 'cambiarEstado']
)->name('citas.estado');

Route::resource('citas', CitaController::class)
    ->except(['destroy']);

    Route::resource('tratamientos', TratamientoController::class)
    ->except(['destroy']);

    Route::resource('expedientes', ExpedienteController::class)
    ->except(['destroy']);

    Route::resource('consultas', ConsultaController::class)
    ->except(['destroy']);

});