<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Cita;
use App\Models\Tratamiento;

use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        // Pacientes registrados
        $totalPacientes = Paciente::count();

        // Citas de hoy
       $citasHoy = Cita::whereDate('fecha_cita', today())
       ->whereIn('estado', ['PENDIENTE', 'CONFIRMADA'])
       ->count();

        // Tratamientos activos
        $tratamientosActivos = Tratamiento::where('estado', 'ACTIVO')->count();

        // Seguimientos pendientes
        $seguimientosPendientes = 0;

        // Los siguientes módulos se conectarán
        // cuando sus tablas estén implementadas.

        $proximasCitas = Cita::with('paciente')
    ->whereIn('estado', ['PENDIENTE', 'CONFIRMADA'])
    ->where(function ($query) {
        $query->whereDate('fecha_cita', '>', today())
            ->orWhere(function ($q) {
                $q->whereDate('fecha_cita', today())
                  ->whereTime('hora_inicio', '>=', now()->format('H:i:s'));
            });
    })
    ->orderBy('fecha_cita')
    ->orderBy('hora_inicio')
    ->limit(5)
    ->get();
        $listaSeguimientos = collect();

        return view('dashboard', compact(
            'totalPacientes',
            'citasHoy',
            'tratamientosActivos',
            'seguimientosPendientes',
            'proximasCitas',
            'listaSeguimientos'
        ));
    }
}
