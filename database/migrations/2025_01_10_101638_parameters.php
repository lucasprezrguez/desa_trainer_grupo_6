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
        Schema::create('parameters', function (Blueprint $table) {
            $table->id(); // Clave primaria auto-incremental
            $table->foreignId('device_id')->constrained('devices')->onDelete('cascade'); // Llave foránea hacia 'devices'
            $table->integer('discharges'); // Número de descargas
            $table->enum('discharge_interval', [1, 2, 'infinito']); // Intervalo de descarga
            $table->boolean('tempo'); // Indicador booleano para 'tempo'
            $table->enum('basic_rcp_duration', [1, 1.5, 2, 2.5, 3]); // Duración básica de RCP
            $table->enum('non_indicated_rcp_duration', [1, 1.5, 2, 2.5, 3]); // Duración no indicada de RCP
            $table->string('predetermined_scenario'); // Escenario predeterminado (campo tipo string)
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
