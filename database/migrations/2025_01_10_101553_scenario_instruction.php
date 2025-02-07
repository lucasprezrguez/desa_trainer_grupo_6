<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('scenario_instruction', function (Blueprint $table) {
            $table->id(); // Clave primaria auto-incremental
            $table->foreignId('scenario_id')->constrained('scenarios')->onDelete('cascade'); // Llave foránea
            $table->foreignId('instruction_id')->constrained('instructions')->onDelete('cascade'); // Llave foránea
            $table->integer('order'); // Orden de la instrucción en el escenario
            $table->integer('repeticiones')->default(1); // Número de repeticiones
            $table->json('parametros')->nullable(); // Parámetros adicionales en formato JSON
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    public function down() {
        Schema::dropIfExists('scenario_instruction');
    }
};

