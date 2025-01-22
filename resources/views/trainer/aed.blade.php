@extends('layouts.app')

<div class="w-screen h-screen flex justify-center items-center bg-gray-200">
    <!-- Contenedor del DESA Trainer -->
    <div class="bg-neutral-400 rounded-lg shadow-xl w-full h-full flex flex-col justify-around items-center relative">
        <!-- Botón de encendido/apagado -->
        <div class="bg-green-500 hover:bg-green-600 w-32 h-32 rounded-full shadow-lg flex items-center justify-center text-white font-bold cursor-pointer mt-4 border-2 border-green-950">
            On/Off
        </div>

        <!-- Pantalla con marco negro -->
        <div class="bg-black w-custom h-custom rounded-xl shadow-inner flex flex-col items-center justify-between p-4 relative">
            <!-- Contenedor Flexbox -->
            <div class="flex flex-col items-center justify-around w-full h-full">
                <!-- Texto superior en el marco negro -->
                <div class="text-center font-bold text-3xl text-white">SOLO ENTRENAMIENTO</div>

                <!-- Contenido de la pantalla -->
                <div class="bg-amber-400 w-96 h-80 rounded-xl flex flex-col items-center justify-center text-gray-900 p-4">
                    <!-- Espacio para contenido central (vacío por ahora) -->
                </div>

                <!-- Logo en el marco negro -->
                <div class="text-center text-sm font-light text-white">
                    <span class="font-bold">Laerdal</span><br />
                    Helping save lives
                </div>
            </div>
        </div>

        <!-- Botón de descarga -->
        <div class="bg-orange-600 hover:bg-red-700 w-40 h-40 rounded-full shadow-lg flex items-center justify-center text-white font-bold cursor-pointer mb-6 border-2 border-orange-950">
            Choque
        </div>
    </div>
</div>

<style>
    .w-custom {
        width: 32rem;
    }
    .h-custom {
        height: 30rem;
    }
</style>
