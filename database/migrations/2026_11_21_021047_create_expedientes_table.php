
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pc_expedientes', function (Blueprint $table) {
            $table->id('id_expediente');

            $table->unsignedBigInteger('id_paciente')->unique();

            $table->date('fecha_apertura');

            $table->string('tipo_sangre', 10)->nullable();
            $table->text('alergias')->nullable();
            $table->text('enfermedades_cronicas')->nullable();
            $table->text('medicamentos_actuales')->nullable();
            $table->text('antecedentes_personales')->nullable();
            $table->text('antecedentes_familiares')->nullable();
            $table->text('antecedentes_quirurgicos')->nullable();
            $table->text('antecedentes_podologicos')->nullable();
            $table->text('motivo_consulta_inicial')->nullable();
            $table->text('observaciones_generales')->nullable();

            $table->unsignedBigInteger('registrado_por')->nullable();

            $table->timestamps();

            $table->foreign('id_paciente')
                ->references('id_paciente')
                ->on('pc_pacientes')
                ->restrictOnDelete();

            $table->foreign('registrado_por')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pc_expedientes');
    }
};
