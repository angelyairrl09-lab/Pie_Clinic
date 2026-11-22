<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pc_seguimientos', function (Blueprint $table) {
            $table->id('id_seguimiento');
            $table->unsignedBigInteger('id_tratamiento');
            $table->date('fecha_consulta');
            $table->text('observaciones')->nullable();
            $table->text('evolucion')->nullable();
            $table->text('avances')->nullable();
            $table->dateTime('proxima_cita')->nullable();
            $table->unsignedBigInteger('registrado_por');
            $table->timestamps();
            $table->index(['id_tratamiento', 'fecha_consulta']);
            $table->foreign('id_tratamiento')->references('id_tratamiento')->on('pc_tratamientos')->restrictOnDelete();
            $table->foreign('registrado_por')->references('id')->on('users')->restrictOnDelete();
        });
    }
    public function down(): void { Schema::dropIfExists('pc_seguimientos'); }
};
