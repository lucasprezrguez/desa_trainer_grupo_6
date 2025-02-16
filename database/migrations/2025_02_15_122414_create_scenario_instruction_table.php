<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScenarioInstructionTable extends Migration
{
    // Método que se ejecuta al aplicar la migración
    public function up()
    {
        // Crear la tabla pivote 'scenario_instruction' con las columnas especificadas
        Schema::create('scenario_instruction', function (Blueprint $table) {
            $table->id();
            // Llave foránea a la tabla 'scenarios'
            $table->foreignId('scenario_id')
                  ->constrained('scenarios', 'scenario_id')
                  ->onDelete('cascade');
                  
            // Llave foránea a la tabla 'instructions'
            $table->foreignId('instruction_id')
                  ->constrained('instructions', 'instruction_id')
                  ->onDelete('cascade');
            
            $table->integer('order'); // Orden de la instrucción en el escenario
            $table->integer('reps')->default(1); // Número de repeticiones, por defecto 1
            $table->json('params')->nullable(); // Parámetros adicionales en formato JSON
            $table->timestamps(); // Columnas created_at y updated_at
        });
    }

    // Método que se ejecuta al revertir la migración
    public function down()
    {
        // Eliminar la tabla 'scenario_instruction' si existe
        Schema::dropIfExists('scenario_instruction');
    }
}