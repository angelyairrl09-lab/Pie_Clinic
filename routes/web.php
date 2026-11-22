<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiagnosticoController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\TratamientoController;
use App\Http\Controllers\ExpedienteController;
use App\Http\Controllers\ConsultaController;
use App\Http\Controllers\SeguimientoController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\FinanzaController;
use App\Http\Controllers\ReporteController;

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

    Route::get('/tratamientos/{tratamiento}/pdf', [TratamientoController::class, 'pdf'])
    ->name('tratamientos.pdf');

    Route::delete('seguimientos/{seguimiento}/fotografias/{fotografia}', [SeguimientoController::class, 'eliminarFotografia'])->name('seguimientos.fotografias.destroy');
    Route::resource('seguimientos', SeguimientoController::class)->except('destroy');

      Route::get('/inventario', [InventarioController::class, 'index'])->name('inventario.index');
    Route::get('/inventario/crear', [InventarioController::class, 'create'])->name('inventario.create');
    Route::post('/inventario', [InventarioController::class, 'store'])->name('inventario.store');
    Route::get('/inventario/{producto}', [InventarioController::class, 'show'])->name('inventario.show');
    Route::get('/inventario/{producto}/editar', [InventarioController::class, 'edit'])->name('inventario.edit');
    Route::put('/inventario/{producto}', [InventarioController::class, 'update'])->name('inventario.update');
    Route::post('/inventario/{producto}/entradas', [InventarioController::class, 'entrada'])->name('inventario.entrada');
    Route::post('/inventario/{producto}/salidas', [InventarioController::class, 'salida'])->name('inventario.salida');

    Route::get('/finanzas', [FinanzaController::class, 'index'])->name('finanzas.index');
    Route::get('/finanzas/crear', [FinanzaController::class, 'create'])->name('finanzas.create');
    Route::post('/finanzas', [FinanzaController::class, 'store'])->name('finanzas.store');
    Route::get('/finanzas/{movimiento}/editar', [FinanzaController::class, 'edit'])->name('finanzas.edit');
    Route::put('/finanzas/{movimiento}', [FinanzaController::class, 'update'])->name('finanzas.update');
    Route::delete('/finanzas/{movimiento}', [FinanzaController::class, 'destroy'])->name('finanzas.destroy');

    Route::middleware(['auth', 'verified'])->prefix('reportes')->name('reportes.')->group(function () {
    Route::get('/', [ReporteController::class, 'index'])->name('index');
    Route::get('/pdf', [ReporteController::class, 'pdf'])->name('pdf');
    Route::get('/csv', [ReporteController::class, 'csv'])->name('csv');
});

});