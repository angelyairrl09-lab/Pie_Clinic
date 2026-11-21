<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pc_consultas', function (Blueprint $table) {
            $table->id('id_consulta');
            $table->unsignedBigInteger('id_paciente');
            $table->unsignedBigInteger('id_expediente');
            $table->unsignedBigInteger('id_tratamiento')->nullable();
            $table->date('fecha_consulta');
            $table->time('hora_consulta')->nullable();
            $table->text('motivo_consulta');
            $table->text('exploracion')->nullable();
            $table->text('diagnostico')->nullable();
            $table->text('procedimiento')->nullable();
            $table->text('indicaciones')->nullable();
            $table->text('observaciones')->nullable();
            $table->date('proxima_revision')->nullable();
            $table->foreignId('registrado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->foreign('id_paciente')->references('id_paciente')->on('pc_pacientes')->restrictOnDelete();
            $table->foreign('id_expediente')->references('id_expediente')->on('pc_expedientes')->restrictOnDelete();
            $table->foreign('id_tratamiento')->references('id_tratamiento')->on('pc_tratamientos')->nullOnDelete();
            $table->index(['id_paciente', 'fecha_consulta']);
            $table->index(['id_expediente', 'fecha_consulta']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pc_consultas');
    }
};
