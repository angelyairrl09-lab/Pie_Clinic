
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pc_pacientes', function (Blueprint $table) {

            $table->id('id_paciente');

            $table->string('numero_paciente', 30)
                  ->nullable()
                  ->unique();

            $table->string('nombre', 100);
            $table->string('apellido_paterno', 100);
            $table->string('apellido_materno', 100)->nullable();

            $table->date('fecha_nacimiento');

            $table->enum('sexo', [
                'MASCULINO',
                'FEMENINO',
                'OTRO',
                'NO_ESPECIFICADO'
            ]);

            $table->string('telefono', 20)->nullable();
            $table->string('correo', 150)->nullable();

            $table->string('calle', 200)->nullable();
            $table->string('numero_exterior', 20)->nullable();
            $table->string('numero_interior', 20)->nullable();
            $table->string('colonia', 100)->nullable();
            $table->string('municipio', 100)->nullable();
            $table->string('estado', 100)->nullable();
            $table->string('codigo_postal', 5)->nullable();

            $table->string('ocupacion', 100)->nullable();

            $table->string('contacto_emergencia', 200)
                  ->nullable();

            $table->string('telefono_emergencia', 20)
                  ->nullable();

            $table->string('parentesco_emergencia', 50)
                  ->nullable();

            $table->text('observaciones')->nullable();

            $table->boolean('activo')->default(true);

            $table->foreignId('registrado_por')
                  ->constrained('users')
                  ->restrictOnDelete();

            $table->timestamps();

            $table->index([
                'apellido_paterno',
                'apellido_materno',
                'nombre'
            ]);

            $table->index('telefono');
            $table->index('activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pc_pacientes');
    }
};
