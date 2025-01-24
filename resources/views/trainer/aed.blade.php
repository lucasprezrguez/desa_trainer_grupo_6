@extends('layouts.app')

<div class="w-screen h-screen flex justify-center items-center bg-gray-200 bg-contain bg-center bg-no-repeat" style="background-image: url('{{ asset('images/laerdal_background.png') }}');">
    <!-- Contenedor del DESA Trainer -->
    <div class=" rounded-lg shadow-xl w-full h-full flex flex-col justify-evenly items-center relative">
        <!-- Botón de encendido/apagado -->
        <div class="bg-green-500 hover:bg-green-600 w-24 h-24 rounded-full flex items-center justify-center text-white font-bold cursor-pointer mt-4 border-2 border-green-950 transform active:scale-75 transition-transform neum">
            <i class="ri-shut-down-line text-5xl"></i>
        </div>

        <!-- Pantalla con marco negro -->
        <div class="bg-neutral-700 w-[32rem] h-[32rem] border-2 border-neutral-300 rounded-3xl shadow-inner flex flex-col items-center justify-between p-4 relative">
            <!-- Contenedor Flexbox -->
            <div class="flex flex-col items-center justify-around w-full h-full">
                <!-- Texto superior en el marco negro -->
                <div class="text-center font-bold text-2xl text-neutral-400 uppercase border-2 border-black px-4 py-1 rounded-md mb-4">
                    SOLO ENTRENAMIENTO
                </div>

                <!-- Contenido de la pantalla -->
                <div class="bg-amber-400 w-[80%] aspect-[1.21] rounded-3xl flex items-center justify-center mb-4">
                    <img src="{{ asset('images/screen.png') }}" alt="Screen Content" class="object-contain rounded-3xl max-w-full max-h-full">
                </div>

                <!-- Logo en el marco negro -->
                <div class="flex flex-col items-center text-center text-sm font-light text-white">
                    <img src="{{ asset('images/laerdal.png') }}" alt="Laerdal Logo" class="w-[30%] object-contain">
                </div>
            </div>
        </div>

        <!-- Botón de descarga -->
        <div class="bg-orange-600 hover:bg-red-700 w-32 h-32 rounded-full flex items-center justify-center text-white font-bold cursor-pointer mb-6 border-2 border-orange-950 transform active:scale-75 transition-transform neum">
            <i class="ri-flashlight-fill text-5xl"></i>
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

    .neum {
        box-shadow:  0px 0px 4px 10px #6f6f6f;
    }
</style>
