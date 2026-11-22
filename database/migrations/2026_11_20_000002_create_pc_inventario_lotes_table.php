<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pc_inventario_lotes', function (Blueprint $table) {
            $table->id('id_lote');
            $table->unsignedBigInteger('id_producto');
            $table->string('numero_lote', 100);
            $table->date('fecha_caducidad')->nullable();
            $table->unsignedInteger('existencia')->default(0);
            $table->timestamps();
            $table->foreign('id_producto')->references('id_producto')->on('pc_inventario_productos')->restrictOnDelete();
            $table->unique(['id_producto', 'numero_lote', 'fecha_caducidad'], 'pc_lote_producto_numero_cad_unique');
            $table->index(['id_producto', 'fecha_caducidad']);
        });
    }
    public function down(): void { Schema::dropIfExists('pc_inventario_lotes'); }
};
