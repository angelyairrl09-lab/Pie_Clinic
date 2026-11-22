<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pc_inventario_productos', function (Blueprint $table) {
            $table->id('id_producto');
            $table->string('codigo', 60)->unique();
            $table->string('nombre', 180);
            $table->enum('categoria', ['MEDICAMENTO', 'MATERIAL', 'PRODUCTO']);
            $table->string('unidad', 40)->default('pieza');
            $table->unsignedInteger('stock_minimo')->default(0);
            $table->decimal('precio_compra', 12, 2)->nullable();
            $table->decimal('precio_venta', 12, 2)->nullable();
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('pc_inventario_productos'); }
};
