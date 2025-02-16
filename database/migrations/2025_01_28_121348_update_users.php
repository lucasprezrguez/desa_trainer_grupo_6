<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Método que se ejecuta al aplicar la migración
    public function up(): void
    {
        // Modificar la tabla 'users' para añadir la columna 'roles'
        Schema::table('users', function (Blueprint $table){
            $table->string('roles')->default('alumno'); // Añadir columna 'roles' con valor por defecto 'alumno'
        });
    }

    // Método que se ejecuta al revertir la migración
    public function down(): void
    {
        // Revertir los cambios eliminando la columna 'roles'
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('roles'); // Eliminar columna 'roles'
        });
    }
};
