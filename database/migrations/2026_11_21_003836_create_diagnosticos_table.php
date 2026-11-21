
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pc_diagnosticos', function (Blueprint $table) {
            $table->id('id_diagnostico');

            $table->foreignId('id_paciente')
                ->constrained('pc_pacientes', 'id_paciente')
                ->restrictOnDelete();

            $table->date('fecha_diagnostico');

            $table->string('titulo', 200);
            $table->text('descripcion');
            $table->text('observaciones')->nullable();
            $table->text('recomendaciones')->nullable();

            $table->foreignId('registrado_por')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamps();

            $table->index(['id_paciente', 'fecha_diagnostico']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pc_diagnosticos');
    }
};
