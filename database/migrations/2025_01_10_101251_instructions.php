<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('instructions', function (Blueprint $table) {
            $table->id(); // Clave primaria auto-incremental
            $table->text('text_content'); // Contenido de texto
            $table->boolean('require_action'); // Indicador de si requiere acción (true/false)
            $table->enum('action_type', ['discharge', 'electrodes', 'none']); // Tipo de acción con valores específicos
            $table->integer('waiting_time'); // Tiempo de espera en segundos (u otra unidad)
            $table->timestamps(); // Columnas 'created_at' y 'updated_at'
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
