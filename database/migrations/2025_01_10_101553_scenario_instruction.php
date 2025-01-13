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
        Schema::create('scenario_instruction', function (Blueprint $table) {
            $table->id(); // Clave primaria auto-incremental
            $table->foreignId('scenario_id')->constrained('scenarios')->onDelete('cascade'); // Llave foránea hacia 'scenarios'
            $table->foreignId('instruction_id')->constrained('instructions')->onDelete('cascade'); // Llave foránea hacia 'instructions'
            $table->integer('order'); // Orden de la instrucción en el escenario
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
