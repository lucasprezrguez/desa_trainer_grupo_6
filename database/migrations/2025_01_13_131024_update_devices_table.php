<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            // Elimina las columnas existentes
            $table->dropColumn(['on_led', 'pause_state', 'display_message']);
            
            // Añade las nuevas columnas
            $table->boolean('status')->default(false); // Estado del dispositivo (en línea o no)
            $table->unsignedTinyInteger('progress')->default(0); // Progreso en %
            $table->string('current_scenario')->nullable(); // Escenario actual, nullable por defecto
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            // Vuelve a agregar las columnas eliminadas
            $table->boolean('on_led')->default(false);
            $table->boolean('pause_state')->default(false);
            $table->text('display_message')->nullable();
            
            // Elimina las columnas añadidas
            $table->dropColumn(['status', 'progress', 'current_scenario']);
        });
    }
}