<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pc_agenda_bloqueo', function (Blueprint $table) {
            $table->unsignedTinyInteger('id')->primary();
        });

        DB::table('pc_agenda_bloqueo')->insert(['id' => 1]);
    }

    public function down(): void
    {
        Schema::dropIfExists('pc_agenda_bloqueo');
    }
};
