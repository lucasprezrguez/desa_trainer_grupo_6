@extends('layouts.app')

@section('content')
    <style>
        #aed-container {
            width: var(--w-device);
            height: var(--h-device);
        }
        #countdown-timer {
            display: inline-block;
        }
        #progress-bar {
            width: 80%;
            height: 4px;
            background-color: #5c5c5c;
            border-radius: 25px;
            overflow: hidden;
            display: inline-block;
        }
        #progress-bar-fill {
            height: 100%;
            background-color: #ffb900;
            width: 0;
            transition: width 0.1s;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <div x-data="{
        isOn: false,
        backgroundImage: '{{ asset('images/device.png') }}',
        logCount: 0, // Contador para el número de veces que se pulsa el botón de descarga
        scenarioInstruction: {{ $scenarioInstruction }},
        instructions: {{$instructions}},
        currentInstruction: null,
        screen: '',
        showImage: true,
        selectedScenarioId: 0,
        scenarioInstructionSelected: null,
        countdownInterval: null,
    
        selectScenario(id) {
            
            this.selectedScenarioId = id;
            this.isOn = true;
            this.backgroundImage = this.isOn ? '{{ asset('images/device_pads.png') }}' : '{{ asset('images/device.png') }}';
            this.screen = '';
            this.logCount = 0;

            confirm('¿Estás seguro de que quieres empezar la simulación?');

            this.scenarioInstructionSelected = this.scenarioInstruction.filter(
                scenario => scenario.scenario_id == this.selectedScenarioId
            );

            console.log('🔎 Filtradas Instrucciones:', this.scenarioInstructionSelected);

            if (!this.scenarioInstructionSelected.length) {
                console.error('⚠️ No se encontraron instrucciones para este escenario.');
                return;
            }

            this.$refs.closeButton.click();
            console.log('✅ Selected scenario id: ', this.selectedScenarioId);

            this.logShockButtonPress();
        },
        togglePower() {
            this.isOn = !this.isOn;
            this.backgroundImage = this.isOn ? '{{ asset('images/device_pads.png') }}' : '{{ asset('images/device.png') }}';
            this.logCount = 0;
            this.screen = '';
            if (this.isOn) {
                this.startLogging(); // Iniciar la función cuando el dispositivo se enciende
            } else {
                {{-- this.logCount = 0; // Reiniciar el contador cuando el dispositivo se apaga --}}
            }
        },
        logShockButtonPress() {
            if (!this.scenarioInstructionSelected || !this.scenarioInstructionSelected.length) {
                console.error('❌ No hay instrucciones seleccionadas.');
                return;
            }

            if (this.logCount >= this.scenarioInstructionSelected.length) {
                console.log('✅ Todas las instrucciones han sido ejecutadas.');
                this.logCount = 0;
                this.showImage = true;
                this.togglePower();
                return;
            }

            console.log('CONTADOR: ', this.logCount);
            console.log('🔎 Instrucción actual:', this.scenarioInstructionSelected[this.logCount]);

            const currentScenarioInstruction = this.scenarioInstructionSelected[this.logCount];

            if (!currentScenarioInstruction) {
                console.error(`⚠️ No hay instrucción en la posición ${this.logCount}`);
                return;
            }

            this.currentInstruction = this.instructions.find(
                instruction => parseInt(instruction.instruction_id) === parseInt(currentScenarioInstruction.instruction_id)
            );

            if (!this.currentInstruction) {
                console.error(`⚠️ No se encontró la instrucción con ID ${currentScenarioInstruction.instruction_id}`);
                return;
            }

            console.log(`Instrucción actual: `, this.currentInstruction);

            const utterance = new SpeechSynthesisUtterance(currentScenarioInstruction.params);
            speechSynthesis.cancel();

            if (this.currentInstruction.require_action) {
                this.showImage = false;
                this.screen = currentScenarioInstruction.params;
                speechSynthesis.speak(utterance);
                this.logCount += 1;
            } else {
                console.log(`Esperando ${this.currentInstruction.waiting_time} segundos antes de avanzar...`);
                this.showImage = false;
                this.screen = currentScenarioInstruction.params;
                speechSynthesis.speak(utterance);
                this.startProgressBar(this.currentInstruction.waiting_time);
                this.startCountdown(this.currentInstruction.waiting_time);
                setTimeout(() => {
                    clearInterval(this.countdownInterval);
                    this.logCount += 1;
                    this.logShockButtonPress();
                }, this.currentInstruction.waiting_time * 1000);
            }
        },
        startProgressBar(waitingTime) {
            $('#progress-bar-fill').css('width', '0');
            $('#progress-bar-fill').animate({ width: '100%' }, waitingTime * 1000);
        },
        startCountdown(waitingTime) {
            let remainingTime = waitingTime;
            $('#countdown-timer').text(this.formatTime(remainingTime));
            this.countdownInterval = setInterval(() => {
                remainingTime -= 1;
                $('#countdown-timer').text(this.formatTime(remainingTime));
                if (remainingTime <= 0) {
                    clearInterval(this.countdownInterval);
                }
            }, 1000);
        },
        formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = seconds % 60;
            return `${minutes}:${remainingSeconds < 10 ? '0' : ''}${remainingSeconds}`;
        },
        updateBackgroundSize() {
            this.$nextTick(() => {
                const backgroundImage = document.getElementById('background-image');
                if (!backgroundImage) return;
    
                const { clientWidth: width, clientHeight: height } = backgroundImage;
                document.documentElement.style.setProperty('--w-device', `${width}px`);
                document.documentElement.style.setProperty('--h-device', `${height * 0.87}px`);
            });
        }
    
    }" x-init="updateBackgroundSize();
    document.addEventListener('DOMContentLoaded', updateBackgroundSize);
    speechSynthesis.cancel();
    " @resize.window="updateBackgroundSize()"
        class="w-screen h-screen flex justify-center items-center bg-neutral-800 relative">
        <!-- Imagen de fondo -->
        <img id="background-image" :src="backgroundImage" alt="Dispositivo LAERDAL DEA de entrenamiento 3"
            class="absolute inset-0 mx-auto h-full object-contain drop-shadow-2xl">
        <!-- Contenedor del DESA Trainer -->
        <div class="rounded-lg shadow-3xl w-full h-full flex flex-col justify-between items-center relative"
            style="width: var(--w-device); height: var(--h-device);">
            <!-- Contenedor del LED y el botón de encendido/apagado -->
            <div class="flex flex-col items-center justify-between gap-4">
                <!-- LED indicador -->
                <div id="led-indicator"
                    :class="isOn ? 'bg-green-500 border-green-950' : 'bg-neutral-700 border-neutral-700'"
                    class="w-8 h-8 rounded-full border-2"></div>
                <!-- Botón de encendido/apagado -->
                <button @click="togglePower" id="power-button"
                    class="bg-green-500 w-24 h-24 rounded-full flex items-center justify-center text-white font-bold cursor-pointer border-2 border-green-950 transform active:scale-90">
                    <i class="ri-shut-down-line text-5xl"></i>
                </button>
            </div>
            <!-- Pantalla con marco negro -->
            <div
                class="bg-neutral-700 w-[72%] aspect-square border-2 border-neutral-400 rounded-3xl inset-shadow-sm flex flex-col items-center justify-between p-4 relative">
                <!-- Contenedor Flexbox -->
                <div class="flex flex-col items-center justify-around w-full h-full">
                    <!-- Botón superior en el marco negro -->
                    <p class="text-center font-bold text-xl text-neutral-400 uppercase border-2 border-black rounded-md py-1 px-4">
                        SOLO ENTRENAMIENTO
                    </p>
                    <!-- Contenido de la pantalla (Texto dinámico) -->
                    <div
                        class="w-[80%] aspect-[1.21] flex items-center justify-center bg-black text-white text-2xl font-bold rounded-3xl">
                        <img x-show="showImage" src="{{ asset('images/screen.png') }}" alt="Screen Content"
                            class="object-contain rounded-3xl max-w-full max-h-full">
                        <span x-text="screen" class="screen-text text-sm"></span>
                    </div>
                    <!-- Barra de progreso y contador -->
                    <div class="flex items-center justify-center w-[80%]">
                        <button id="drawer-button" @click="drawerOpen = true" href="" class="animate-pulse underline text-amber-400" x-show="!selectedScenarioId" type="button" data-drawer-target="drawer-scenarios" data-drawer-show="drawer-scenarios"
                        aria-controls="drawer-scenarios">Elegir escenario</button>
                        <div id="progress-bar" x-show="selectedScenarioId">
                            <div id="progress-bar-fill"></div>
                        </div>
                        <div id="countdown-timer" class="font-mono text-xs text-neutral-50 ms-1" x-show="selectedScenarioId"></div>
                    </div>
                    <!-- Logo en el marco negro -->
                    {{-- <div class="flex flex-col items-center text-center text-sm font-light text-white">
                        <img src="{{ asset('images/laerdal.png') }}" alt="Laerdal Logo" class="w-[30%] object-contain">
                    </div> --}}
                </div>
            </div>
            <!-- Botón de descarga -->
            <button @click="logShockButtonPress" id="shock-button"
                class="w-32 h-32 flex items-center justify-center text-white font-bold cursor-pointer mb-6 transform active:scale-90">
                <img src="{{ asset('images/choque.png') }}" alt="Shock Button" class="object-contain w-full h-full">
            </button>
        </div>

        <!-- Cajonera -->
        <div id="drawer-scenarios"
            class="fixed top-0 left-0 z-40 h-screen py-2 px-4 overflow-y-auto transition-transform -translate-x-full bg-neutral-50 w-64 flex flex-col rounded-r-2xl shadow-xl"
            tabindex="-1" aria-labelledby="drawer-scenarios-label">
            <!-- Encabezado de la Cajonera -->
            <div class="flex justify-between items-center mb-2">
                <h5 id="drawer-scenarios-label" class="text-base font-semibold text-gray-500 uppercase">Escenarios</h5>
                <button type="button" x-ref="closeButton" data-drawer-hide="drawer-scenarios"
                    aria-controls="drawer-scenarios"
                    class="text-gray-400 bg-transparent w-8 h-8 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-lg flex items-center justify-center">
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
                    @foreach ($scenarios as $scenario)
                        <li style="cursor: pointer;">
                            <a class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group w-full"
                                @click="selectScenario({{ $scenario->scenario_id }})">
                                <img src="{{ asset($scenario->image_url) }}" alt="Escenario {{ $loop->iteration }}"
                                    class="w-auto h-8">
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <!-- Botón de cerrar sesión -->
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="me-2 mb-2">
                @csrf
                <button type="submit"
                    class="text-gray-900 bg-white border border-gray-300 w-full focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded text-sm px-5 py-2.5">
                    <i class="ri-logout-box-line me-2"></i>Finalizar sesión
                </button>
            </form>
        </div>
    </div>
