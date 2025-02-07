@extends('layouts.app')

@section('content')
<style>
    #aed-container {
        width: var(--w-device);
        height: var(--h-device);
    }
</style>
<div x-data="{
    isOn: false,
    backgroundImage: '{{ asset('images/device.png') }}',
    logCount: 0, // Contador para el número de veces que se pulsa el botón de descarga
    togglePower() {
        this.isOn = !this.isOn;
        this.backgroundImage = this.isOn ? '{{ asset('images/device_pads.png') }}' : '{{ asset('images/device.png') }}';
        if (this.isOn) {
            this.startLogging(); // Iniciar la función cuando el dispositivo se enciende
        } else {
            this.logCount = 0; // Reiniciar el contador cuando el dispositivo se apaga
        }
    },
    startLogging() {
        console.log('Dispositivo encendido. Listo para registrar pulsaciones.');
    },
    logShockButtonPress() {
        if (this.isOn) {
            this.logCount += 1;
            console.log({{$scenarioInstruction}});
            console.log(`Botón de descarga pulsado ${this.logCount} veces.`);
        } else {
            console.log('El dispositivo está apagado. Enciéndelo para registrar pulsaciones.');
        }
    },
    updateBackgroundSize() {
        setTimeout(() => {
            const backgroundImage = document.getElementById('background-image');
            if (!backgroundImage) return;

            const { clientWidth: width, clientHeight: height } = backgroundImage;
            document.documentElement.style.setProperty('--w-device', `${width}px`);
            document.documentElement.style.setProperty('--h-device', `${height * 0.87}px`);
        }, 10);
    }
}"
    x-init="updateBackgroundSize(); document.addEventListener('DOMContentLoaded', updateBackgroundSize)"
    @resize.window="updateBackgroundSize()"
    class="w-screen h-screen flex justify-center items-center bg-neutral-800 relative">
    <!-- Imagen de fondo -->
    <img id="background-image" :src="backgroundImage" alt="Dispositivo LAERDAL DEA de entrenamiento 3" class="absolute inset-0 mx-auto h-full object-contain drop-shadow-2xl">
    
    <!-- Contenedor del DESA Trainer -->
    <div id="aed-container" class="rounded-lg shadow-3xl flex flex-col justify-between items-center relative">
        <!-- Contenedor del LED y el botón de encendido/apagado -->
        <div class="flex flex-col items-center justify-between gap-4">
            <!-- LED indicador -->
            <div id="led-indicator" :class="isOn ? 'bg-green-500 border-green-950' : 'bg-neutral-700 border-neutral-700'" class="w-8 h-8 rounded-full border-2"></div>
            <!-- Botón de encendido/apagado -->
            <button @click="togglePower" id="power-button" class="bg-green-500 w-24 h-24 rounded-full flex items-center justify-center text-white font-bold cursor-pointer border-2 border-green-950 transform active:scale-90">
                <i class="ri-shut-down-line text-5xl"></i>
            </button>
        </div>
        <!-- Pantalla con marco negro -->
        <div class="bg-neutral-700 w-[72%] aspect-square border-2 border-neutral-400 rounded-3xl inset-shadow-sm flex flex-col items-center justify-between p-4 relative">
            <!-- Contenedor Flexbox -->
            <div class="flex flex-col items-center justify-around w-full h-full">
                <!-- Botón superior en el marco negro -->
                <button id="drawer-button" class="text-center font-bold text-base sm:text-lg md:text-2xl lg:text-2xl xl:text-2xl text-neutral-400 uppercase border-2 border-black rounded-md px-4 py-2" type="button" data-drawer-target="drawer-scenarios" data-drawer-show="drawer-scenarios" aria-controls="drawer-scenarios">
                    ELEGIR ESCENARIO
                </button>
                <!-- Contenido de la pantalla -->
                <div class="w-[80%] aspect-[1.21] flex items-center justify-center">
                    <img src="{{ asset('images/screen.png') }}" alt="Screen Content" class="object-contain rounded-3xl max-w-full max-h-full">
                </div>
                <!-- Logo en el marco negro -->
                <div class="flex flex-col items-center text-center text-sm font-light text-white">
                    <img src="{{ asset('images/laerdal.png') }}" alt="Laerdal Logo" class="w-[30%] object-contain">
                </div>
            </div>
        </div>
        <!-- Botón de descarga -->
        <button @click="logShockButtonPress" id="shock-button" class="w-32 h-32 flex items-center justify-center text-white font-bold cursor-pointer mb-6 transform active:scale-90">
            <img src="{{ asset('images/choque.png') }}" alt="Shock Button" class="object-contain w-full h-full">
        </button>
    </div>
</div>

<!-- Cajonera -->
<div id="drawer-scenarios" class="fixed top-0 left-0 z-40 h-screen py-2 px-4 overflow-y-auto transition-transform -translate-x-full bg-neutral-50 w-64 flex flex-col rounded-r-2xl shadow-xl" tabindex="-1" aria-labelledby="drawer-scenarios-label">
    <!-- Encabezado de la Cajonera -->
    <div class="flex justify-between items-center mb-2">
        <h5 id="drawer-scenarios-label" class="text-base font-semibold text-gray-500 uppercase">Escenarios</h5>
        <button type="button" data-drawer-hide="drawer-scenarios" aria-controls="drawer-scenarios" class="text-gray-400 bg-transparent w-8 h-8 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-lg flex items-center justify-center">
            <i class="ri-close-line"></i>
            <span class="sr-only">Cerrar</span>
        </button>
    </div>

    <!-- Descripción -->
    <p class="text-sm text-gray-500 mb-2">
        A continuación, se presentan varios escenarios que puede seleccionar para comenzar la simulación.
    </p>
    <hr class="h-px my-2 bg-gray-200 border-0">
    <!-- Lista de escenarios -->
    <div class="py-4 overflow-y-auto flex-grow">
        <h5 class="text-xs uppercase font-semibold text-gray-500">AED TRAINER 3</h5>
        <ul class="space-y-2 font-medium bg-white shadow-sm rounded-lg p-2 mt-2">
            @foreach($scenarios as $scenario)
                <li>
                    <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
                        <i class="ri-number-{{ $loop->iteration }} text-gray-500"></i>
                        <img src="{{ asset($scenario->image_url) }}" alt="Escenario {{ $loop->iteration }}" class="w-auto h-8 ms-3">
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
    <!-- Botón de cerrar sesión -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="me-2 mb-2">
        @csrf
        <button type="submit" class="text-gray-900 bg-white border border-gray-300 w-full focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded text-sm px-5 py-2.5">
            <i class="ri-logout-box-line me-2"></i>Finalizar sesión
        </button>
    </form>
</div>