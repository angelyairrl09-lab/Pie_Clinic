
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pc_citas', function (Blueprint $table) {
            $table->id('id_cita');

            $table->foreignId('id_paciente')
                ->constrained('pc_pacientes', 'id_paciente')
                ->restrictOnDelete();

            $table->date('fecha_cita');
            $table->time('hora_inicio');
            $table->unsignedSmallInteger('duracion_minutos')
                ->default(30);

            $table->string('motivo', 200);
            $table->text('observaciones')->nullable();

            $table->enum('estado', [
                'PENDIENTE',
                'CONFIRMADA',
                'ATENDIDA',
                'CANCELADA',
                'NO_ASISTIO'
            ])->default('PENDIENTE');

            $table->foreignId('registrado_por')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamps();

            $table->index(['fecha_cita', 'hora_inicio']);
            $table->index(['id_paciente', 'fecha_cita']);
            $table->index(['estado', 'fecha_cita']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pc_citas');
    }
};
