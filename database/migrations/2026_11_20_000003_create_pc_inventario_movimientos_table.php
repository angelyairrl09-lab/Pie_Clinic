<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pc_inventario_movimientos', function (Blueprint $table) {
            $table->id('id_movimiento');
            $table->unsignedBigInteger('id_producto');
            $table->unsignedBigInteger('id_lote');
            $table->enum('tipo', ['ENTRADA', 'SALIDA']);
            $table->unsignedInteger('cantidad');
            $table->string('motivo', 250);
            $table->unsignedBigInteger('registrado_por')->nullable();
            $table->timestamps();
            $table->foreign('id_producto')->references('id_producto')->on('pc_inventario_productos')->restrictOnDelete();
            $table->foreign('id_lote')->references('id_lote')->on('pc_inventario_lotes')->restrictOnDelete();
            $table->index(['id_producto', 'created_at']);
        });
    }
    public function down(): void { Schema::dropIfExists('pc_inventario_movimientos'); }
};
