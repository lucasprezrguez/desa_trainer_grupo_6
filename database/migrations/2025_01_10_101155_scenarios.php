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
        Schema::create('scenarios', function (Blueprint $table) {
            $table->id(); // Columna 'id' (clave primaria auto-incremental)
            $table->string('name'); // Columna 'name' (texto corto)
            $table->text('description')->nullable(); // Columna 'description' (texto largo, nullable)
            $table->integer('discharge_numbers'); // Columna 'discharge_numbers' (entero)
            $table->integer('min_interval'); // Columna 'min_interval' (entero)
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
