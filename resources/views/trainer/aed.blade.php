@extends('layouts.app')

<div id="background-container" class="w-screen h-screen flex justify-center items-center bg-neutral-800 bg-contain bg-center bg-no-repeat" style="background-image: url('{{ asset('images/device.png') }}');">
    <!-- Contenedor del DESA Trainer -->
    <div class="rounded-lg shadow-3xl w-full h-full flex flex-col justify-evenly items-center relative">
        <!-- Contenedor del LED y el botón de encendido/apagado -->
        <div class="flex flex-col items-center justify-between">
            <!-- LED indicador -->
            <div id="led-indicator" class="bg-neutral-700 w-8 h-8 rounded-full mb-8"></div>
            
            <!-- Botón de encendido/apagado -->
            <div id="power-button" class="bg-green-500 w-24 h-24 rounded-full flex items-center justify-center text-white font-bold cursor-pointer border-2 border-green-950 transform active:scale-90">
                <i class="ri-shut-down-line text-5xl"></i>
            </div>
        </div>

        <!-- Pantalla con marco negro -->
        <div class="bg-neutral-700 w-[32rem] h-[32rem] border-2 border-gray-400 rounded-3xl inset-shadow-sm flex flex-col items-center justify-between p-4 relative">
            <!-- Contenedor Flexbox -->
            <div class="flex flex-col items-center justify-around w-full h-full">
                <!-- Botón superior en el marco negro -->
                <button id="drawer-button" class="text-center font-bold text-2xl text-neutral-400 uppercase border-2 border-black px-4 py-1 rounded-md" type="button" data-drawer-target="drawer-scenarios" data-drawer-show="drawer-scenarios" aria-controls="drawer-scenarios">
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
        <div id="shock-button" class="w-32 h-32 flex items-center justify-center text-white font-bold cursor-pointer mb-6 transform active:scale-90">
            <img src="{{ asset('images/choque.png') }}" alt="Shock Button" class="object-contain w-full h-full">
        </div>
    </div>
</div>

<!-- drawer component -->
<div id="drawer-scenarios" class="fixed top-0 left-0 z-40 h-screen p-4 overflow-y-auto transition-transform -translate-x-full bg-white w-64 flex flex-col" tabindex="-1" aria-labelledby="drawer-scenarios-label">
    <!-- Encabezado del Drawer -->
    <div class="flex justify-between items-center mb-4">
        <h5 id="drawer-scenarios-label" class="text-base font-semibold text-gray-500 uppercase dark:text-gray-400"><i class="ri-heart-pulse-line me-2"></i>Escenarios</h5>
        <button type="button" data-drawer-hide="drawer-scenarios" aria-controls="drawer-scenarios" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 flex items-center justify-center">
            <i class="ri-close-line"></i>
            <span class="sr-only">Cerrar</span>
        </button>
    </div>

    <!-- Descripción -->
    <p class="text-sm text-gray-500 mb-4">
        A continuación, se presentan varios escenarios que puede seleccionar para comenzar la simulación.
    </p>

    <!-- Lista de escenarios -->
    <div class="py-4 overflow-y-auto flex-grow"> <!-- flex-grow permite que este contenido ocupe el espacio restante -->
        <ul class="space-y-2 font-medium">
            <!-- Elementos de la lista -->
            <li>
                <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 group">
                    <i class="ri-number-1 text-gray-500"></i>
                    <img src="{{ asset('images/scenario_1.png') }}" alt="Escenario 1" class="w-auto h-8 ms-3">
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 group">
                    <i class="ri-number-2 text-gray-500"></i>
                    <img src="{{ asset('images/scenario_2.png') }}" alt="Escenario 2" class="w-auto h-8 ms-3">
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 group">
                    <i class="ri-number-3 text-gray-500"></i>
                    <img src="{{ asset('images/scenario_3.png') }}" alt="Escenario 3" class="w-auto h-8 ms-3">
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 group">
                    <i class="ri-number-4 text-gray-500"></i>
                    <img src="{{ asset('images/scenario_4.png') }}" alt="Escenario 4" class="w-auto h-8 ms-3">
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 group">
                    <i class="ri-number-5 text-gray-500"></i>
                    <img src="{{ asset('images/scenario_5.png') }}" alt="Escenario 5" class="w-auto h-8 ms-3">
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 group">
                    <i class="ri-number-6 text-gray-500"></i>
                    <img src="{{ asset('images/scenario_6.png') }}" alt="Escenario 6" class="w-auto h-8 ms-3">
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 group">
                    <i class="ri-number-7 text-gray-500"></i>
                    <img src="{{ asset('images/scenario_7.png') }}" alt="Escenario 7" class="w-auto h-8 ms-3">
                </a>
            </li>
            <li>
                <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 group">
                    <i class="ri-number-8 text-gray-500"></i>
                    <img src="{{ asset('images/scenario_8.png') }}" alt="Escenario 8" class="w-auto h-8 ms-3">
                </a>
            </li>
        </ul>
    </div>

    <!-- Enlace para consultar códigos de los escenarios -->
    <a href="#" class="text-sm text-center text-blue-600 no-underline" data-popover-target="popover-scenario-codes" data-popover-placement="top-end"><i class="ri-information-line me-2"></i>Códigos de los escenarios</a>

</div>

<!-- Popover -->
<div data-popover id="popover-scenario-codes" role="tooltip" class="absolute z-50 invisible inline-block text-sm text-gray-500 transition-opacity duration-300 bg-white border border-gray-200 rounded-lg shadow-xs opacity-0 w-64" data-popper-placement="top-end">
    <div class="px-3 py-2 bg-gray-100 border-b border-gray-200 rounded-t-lg">
        <h3 class="font-semibold text-gray-900">Códigos de los escenarios</h3>
    </div>
    <div class="p-3">
        <img src="{{ asset('images/scenario_codes.png') }}" class="w-full mb-4" alt="Scenario Codes">
        <div class="space-y-2">
            <p>A = Ritmo susceptible de choque</p>
            <p>B = Ritmo no susceptible de choque</p>
            <p>C = Problema detectado en los electrodos</p>
            <p>D = Protocolo RCP primero iniciado</p>
        </div>
    </div>
    <div data-popper-arrow></div>
</div>

<script>
    document.getElementById('power-button').addEventListener('click', function() {
        let led = document.getElementById('led-indicator');
        let background = document.getElementById('background-container');
        if (led.classList.contains('bg-neutral-700')) {
            led.classList.remove('bg-neutral-700');
            led.classList.add('bg-lime-400');
            led.style.boxShadow = "rgba(0, 0, 0, 0.2) 0 -1px 7px 1px, inset #304701 0 -1px 9px, #89FF00 0 2px 12px";
            background.style.backgroundImage = "url('{{ asset('images/device_pads.png') }}')";
        } else {
            led.classList.remove('bg-lime-400');
            led.classList.add('bg-neutral-700');
            led.style.boxShadow = "none";
            background.style.backgroundImage = "url('{{ asset('images/device.png') }}')";
        }
    });
</script>