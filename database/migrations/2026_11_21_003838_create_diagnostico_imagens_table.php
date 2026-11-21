
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pc_diagnostico_imagenes', function (Blueprint $table) {
            $table->id('id_imagen');

            $table->foreignId('id_diagnostico')
                ->constrained('pc_diagnosticos', 'id_diagnostico')
                ->cascadeOnDelete();

            $table->string('ruta_imagen', 500);
            $table->string('nombre_original', 255);
            $table->string('tipo_mime', 50)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pc_diagnostico_imagenes');
    }
};
