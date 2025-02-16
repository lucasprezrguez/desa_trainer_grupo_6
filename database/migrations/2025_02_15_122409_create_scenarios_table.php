<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScenariosTable extends Migration
{
    // Método que se ejecuta al aplicar la migración
    public function up()
    {
        // Crear la tabla 'scenarios' con las columnas especificadas
        Schema::create('scenarios', function (Blueprint $table) {
            $table->bigIncrements('scenario_id');; // Columna ID primaria
            $table->string('scenario_name'); // Nombre del escenario
            $table->string('image_url')->nullable(); // URL de la imagen, puede ser nulo
            $table->timestamps(); // Columnas created_at y updated_at
        });
    }

    // Método que se ejecuta al revertir la migración
    public function down()
    {
        // Eliminar la tabla 'scenarios' si existe
        Schema::dropIfExists('scenarios');
    }
}