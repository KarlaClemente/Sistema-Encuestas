<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('encuesta', function (Blueprint $table) {
            $table->id('id_encuesta');
            $table->unsignedBigInteger('id_tipo_encuesta')->nullable();
            $table->unsignedBigInteger('id_grupo')->nullable();
            $table->unsignedBigInteger('id_plantilla')->nullable();
            $table->string('titulo')->nullable();
            $table->text('descripcion')->nullable();
            $table->dateTime('fecha_inicio')->nullable();
            $table->dateTime('fecha_termino')->nullable();
            $table->text('texto_advertencia')->nullable();
            $table->string('estilo')->nullable();
            $table->boolean('completada')->default(false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('encuesta');
    }
};
