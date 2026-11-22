<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pc_seguimiento_fotografias', function (Blueprint $table) {
            $table->id('id_fotografia');
            $table->unsignedBigInteger('id_seguimiento');
            $table->string('ruta', 255);
            $table->string('nombre_original', 255)->nullable();
            $table->timestamps();
            $table->foreign('id_seguimiento')->references('id_seguimiento')->on('pc_seguimientos')->cascadeOnDelete();
        });
    }
    public function down(): void { Schema::dropIfExists('pc_seguimiento_fotografias'); }
};
