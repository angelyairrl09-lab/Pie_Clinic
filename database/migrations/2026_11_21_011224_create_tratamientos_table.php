<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pc_tratamientos', function (Blueprint $table) {
            $table->id('id_tratamiento');

            $table->foreignId('id_paciente')
                ->constrained('pc_pacientes', 'id_paciente')
                ->restrictOnDelete();

            $table->string('nombre', 200);
            $table->text('descripcion')->nullable();
            $table->text('indicaciones')->nullable();

            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();

            $table->decimal('costo', 10, 2)->nullable();

            $table->enum('estado', [
                'PENDIENTE',
                'ACTIVO',
                'FINALIZADO',
                'CANCELADO'
            ])->default('PENDIENTE');

            $table->foreignId('registrado_por')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(['estado', 'fecha_inicio']);
            $table->index(['id_paciente', 'estado']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pc_tratamientos');
    }
};
