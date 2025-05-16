<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstructionsTable extends Migration
{
    // Método que se ejecuta al aplicar la migración
    public function up()
    {
        // Crear la tabla 'instructions' con las columnas especificadas
        Schema::create('instructions', function (Blueprint $table) {
            $table->bigIncrements('instruction_id'); // Columna ID primaria
            $table->string('instruction_name', 255); // Nombre de la instrucción
            $table->integer('waiting_time'); // Tiempo de espera en segundos
            $table->boolean('require_action'); // Indica si requiere acción del usuario
            $table->longText('additional_info')->nullable(); // Información adicional WYSIWYG
            $table->timestamps(); // Columnas created_at y updated_at
        });
    }

    // Método que se ejecuta al revertir la migración
    public function down()
    {
        // Eliminar la tabla 'instructions' si existe
        Schema::dropIfExists('instructions');
    }
}