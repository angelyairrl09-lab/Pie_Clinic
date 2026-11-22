<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pc_finanzas_movimientos', function (Blueprint $table) {
            $table->id('id_movimiento');
            $table->enum('tipo', ['INGRESO', 'EGRESO']);
            $table->date('fecha');
            $table->string('concepto', 200);
            $table->string('categoria', 100);
            $table->decimal('monto', 12, 2);
            $table->enum('metodo_pago', ['EFECTIVO', 'TRANSFERENCIA', 'TARJETA', 'OTRO'])->default('EFECTIVO');
            $table->string('referencia', 120)->nullable();
            $table->text('observaciones')->nullable();
            $table->unsignedBigInteger('id_tratamiento')->nullable()->index();
            $table->unsignedBigInteger('registrado_por')->nullable()->index();
            $table->timestamps();
            $table->index(['fecha', 'tipo']);
        });
    }
    public function down(): void { Schema::dropIfExists('pc_finanzas_movimientos'); }
};
