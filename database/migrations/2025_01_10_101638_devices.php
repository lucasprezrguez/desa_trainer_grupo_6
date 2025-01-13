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
        Schema::create('devices', function (Blueprint $table) {
            $table->id(); // Clave primaria auto-incremental
            $table->string('name'); // Nombre del dispositivo
            $table->boolean('on_led'); // Indicador booleano para el LED encendido/apagado
            $table->boolean('pause_state'); // Indicador booleano para el estado de pausa
            $table->text('display_message')->nullable(); // Mensaje que se muestra en el display (nullable)
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
