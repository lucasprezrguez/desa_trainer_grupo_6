@extends('layouts.app')

@section('content')
    <style>
        [x-cloak] {
            display: none !important;
        }

        #aed-container {
            transition: visibility 50ms ease-out;
        }

        [drawer-backdrop] {
            backdrop-filter: blur(5px);
        }

        #countdown-timer {
            display: none;
            position: absolute;
            bottom: 10px;
            right: 10px;
            padding: 5px 10px;
            border-radius: 5px;
        }

        #countdown-timer:before {
            font-family: "remixicon";
            content: "\f20e";
            margin-right: 5px;
            font-size: 10px;
            font-weight: 100;
            vertical-align: baseline;
        }

        .crt {
            position: relative;
            overflow: hidden;
            filter: contrast(1.5) brightness(0.9) blur(0.4px);
            animation: flicker 0.15s infinite;
        }

        .crt:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background:
                repeating-linear-gradient(0deg,
                    rgba(0, 0, 0, 0.2) 0px,
                    rgba(0, 0, 0, 0.2) 1px,
                    transparent 1px,
                    transparent 2px),
                repeating-linear-gradient(90deg,
                    rgba(255, 255, 255, 0.08) 0px,
                    rgba(255, 255, 255, 0.08) 1px,
                    transparent 1px,
                    transparent 2px);
            pointer-events: none;
            mix-blend-mode: multiply;
            animation: scanlines 6s linear infinite;
        }

        .crt:after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background:
                radial-gradient(circle at 50% 50%,
                    rgba(0, 0, 0, 0.6) 0%,
                    transparent 70%),
                repeating-radial-gradient(circle at 50% 50%,
                    rgba(0, 0, 0, 0.15) 0%,
                    transparent 0.5px,
                    transparent 4px);
            pointer-events: none;
            mix-blend-mode: overlay;
        }

        @keyframes scanlines {
            0% {
                transform: translateY(0%);
            }

            100% {
                transform: translateY(-100%);
            }
        }

        @keyframes flicker {
            0% {
                filter: contrast(1.3) brightness(0.85) blur(0.4px);
            }

            50% {
                filter: contrast(1.4) brightness(0.8) blur(0.6px);
            }

            100% {
                filter: contrast(1.3) brightness(0.85) blur(0.4px);
            }
        }
    </style>
    <div x-data="{
        isOn: false,
        metronomeBpm: {{ session('metronome_bpm', 110) }},
        backgroundImage: '{{ asset('images/device.png') }}',
        logCount: 0, // Contador para el número de veces que se pulsa el botón de descarga
        scenarioInstruction: {{ $scenarioInstruction }},
        instructions: {{ $instructions }},
        currentInstruction: null,
        screen: '',
        showImage: true,
        selectedScenarioId: 0,
        scenarioInstructionSelected: null,
        countdownInterval: null,
        isBackgroundLoaded: false,
        isMetronomeEnabled: true,
        easterEggClickCount: 0,
        easterEggClick() {
            this.easterEggClickCount++;
            if (this.easterEggClickCount >= 7) {
                window.open('https://youtu.be/zWaymcVmJ-A', '_blank');
                this.easterEggClickCount = 0;
            }
        },
        selectScenario(id) {
    
            this.selectedScenarioId = id;
            this.isOn = true;
            this.backgroundImage = this.isOn ? '{{ asset('images/device_pads.png') }}' : '{{ asset('images/device.png') }}';
            this.screen = '';
            this.logCount = 0;
            $('#countdown-timer').hide();
    
    
            // 🔴 Limpia cualquier temporizador anterior antes de comenzar
            clearInterval(this.countdownInterval);
            this.countdownInterval = null;
    
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
    
            this.stopMetronome(); // Stop metronome before starting a new scenario
    
            const scenarioAnnouncement = new SpeechSynthesisUtterance(
                this.selectedScenarioId > 8 ? 'El Escenario Personalizado va a comenzar.' : `El Escenario ${this.selectedScenarioId} va a comenzar.`
            );
            scenarioAnnouncement.onend = () => {
                this.logShockButtonPress();
            };
            speechSynthesis.speak(scenarioAnnouncement);
        },
        togglePower() {
            this.isOn = !this.isOn;
            this.backgroundImage = this.isOn ? '{{ asset('images/device_pads.png') }}' : '{{ asset('images/device.png') }}';
            this.logCount = 0;
            this.screen = '';
    
            // Ocultar el contador al apagar el dispositivo
            $('#countdown-timer').hide(); // Ocultar el contador
    
            if (!this.isOn) {
                // 🔴 Apagado: Cancelar todo lo relacionado con el escenario
                clearInterval(this.countdownInterval); // Detener la cuenta regresiva
                this.countdownInterval = null;
    
                if (this.timeoutId) {
                    clearTimeout(this.timeoutId); // Detener la ejecución automática de instrucciones
                    this.timeoutId = null;
                }
    
                speechSynthesis.cancel(); // Detener cualquier narración en curso
    
                this.scenarioInstructionSelected = null; // Resetear el escenario
                this.currentInstruction = null;
                this.showImage = true;
    
                console.log('🛑 Dispositivo apagado, escenario detenido.');
                this.stopMetronome();
            } else {
                // 🔵 Encendido: Volver a iniciar desde cero si es necesario
                console.log('✅ Dispositivo encendido.');
                this.startLogging();
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
    
            // 🔴 Cancela cualquier temporizador pendiente antes de iniciar uno nuevo
            if (this.timeoutId) {
                clearTimeout(this.timeoutId);
            }
    
            this.stopMetronome(); // Stop metronome before processing the next instruction
    
            if (this.currentInstruction.require_action) {
                this.showImage = false;
                this.screen = currentScenarioInstruction.params;
                $('#countdown-timer').hide();
                speechSynthesis.speak(utterance);
                this.logCount += 1;
            } else {
                console.log(`Esperando ${this.currentInstruction.waiting_time} segundos antes de avanzar...`);
                this.showImage = false;
                this.screen = currentScenarioInstruction.params;
                speechSynthesis.speak(utterance);
    
                // Mostrar el contador cuando se empieza la cuenta regresiva
                $('#countdown-timer').show();
    
                this.startCountdown(this.currentInstruction.waiting_time);
    
                // 🔴 Guarda el ID del temporizador para poder cancelarlo si es necesario
                this.timeoutId = setTimeout(() => {
                    clearInterval(this.countdownInterval);
                    this.logCount += 1;
                    this.logShockButtonPress();
                }, this.currentInstruction.waiting_time * 1000);
            }
    
            if (parseInt(this.currentInstruction.instruction_id) === 4) {
                if (this.isMetronomeEnabled) {
                    this.playMetronome();
                } else {
                    this.stopMetronome();
                }
            }
        },
        startCountdown(waitingTime) {
            let remainingTime = waitingTime;
    
            // Aseguramos que el temporizador anterior se limpie
            clearInterval(this.countdownInterval);
    
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
                if (!backgroundImage || backgroundImage.clientWidth === 0) return;
    
                const container = this.$refs.aedContainer;
                if (container) container.style.visibility = 'visible';
    
                const width = backgroundImage.clientWidth;
                const height = backgroundImage.clientHeight * 0.87;
    
                document.documentElement.style.setProperty('--w-device', `${width}px`);
                document.documentElement.style.setProperty('--h-device', `${height}px`);
    
                setTimeout(() => {
                    this.isBackgroundLoaded = true;
                }, 2000);
            });
        },
        playMetronome() {
            if (this.metronomeInterval) clearInterval(this.metronomeInterval);
            
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const metronomeSound = () => {
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();
                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);
                oscillator.frequency.value = 500;
                gainNode.gain.setValueAtTime(1, audioContext.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.001, audioContext.currentTime + 0.1);
                oscillator.start(audioContext.currentTime);
                oscillator.stop(audioContext.currentTime + 0.1);
            };
            
            // Usa el BPM de la sesión
            this.metronomeInterval = setInterval(
                metronomeSound, 
                60000 / this.metronomeBpm // ¡Aquí aplicamos el BPM!
            );
        },
        stopMetronome() {
            if (this.metronomeInterval) {
                clearInterval(this.metronomeInterval);
                this.metronomeInterval = null;
            }
        },
        watch: {
            isMetronomeEnabled(newValue) {
                if (newValue) {
                    this.playMetronome();
                } else {
                    this.stopMetronome();
                }
            },
            currentInstruction(newValue) {
                if (parseInt(newValue?.instruction_id) === 4) {
                    if (this.isMetronomeEnabled) {
                        this.playMetronome();
                    } else {
                        this.stopMetronome();
                    }
                }
            }
        }
    }" x-cloak x-init="const bgImage = document.getElementById('background-image');
    if (bgImage.complete) updateBackgroundSize();
    speechSynthesis.cancel();" @resize.window="updateBackgroundSize()"
        class="w-screen h-screen flex justify-center items-center bg-neutral-800 relative">
        <!-- Spinner -->
        <div x-show="!isBackgroundLoaded" class="fixed inset-0 z-50 flex items-center justify-center bg-neutral-800">
            <div role="status">
                <i class="ri-pulse-line text-5xl text-white animate-pulse"></i>
                <span class="sr-only">Cargando...</span>
            </div>
        </div>
        <!-- Imagen de fondo -->
        <img id="background-image" :src="backgroundImage" alt="Dispositivo LAERDAL DEA de entrenamiento 3"
            class="absolute inset-0 m-auto md:h-full object-contain drop-shadow-2xl" @load="updateBackgroundSize">
        <!-- Contenedor del DESA Trainer -->
        <div x-ref="aedContainer" id="aed-container"
            class="rounded-lg shadow-3xl w-full h-full flex flex-col justify-between items-center relative"
            style="width: var(--w-device); height: var(--h-device); visibility: hidden;">
            <!-- Contenedor del LED y el botón de encendido/apagado -->
            <div class="flex flex-col items-center justify-between gap-4">
                <!-- LED indicador -->
                <div id="led-indicator"
                    :class="isOn ? 'bg-green-500 border-green-950' : 'bg-neutral-700 border-neutral-700'"
                    class="w-4 h-4 md:w-8 md:h-8 rounded-full border-2"></div>
                <!-- Botón de encendido/apagado -->
                <button @click="togglePower" id="power-button"
                    class="bg-green-500 w-16 h-16 md:w-24 md:h-24 rounded-full flex items-center justify-center text-white font-bold cursor-pointer border-2 border-green-950 transform active:scale-90">
                    <i class="ri-shut-down-line text-3xl md:text-5xl"></i>
                </button>
            </div>
            <!-- Pantalla con marco negro -->
            <div
                class="bg-neutral-700 w-[72%] aspect-square border-2 border-neutral-400 rounded-xl md:rounded-3xl inset-shadow-sm flex flex-col items-center relative">
                <!-- Contenedor Flexbox -->
                <div class="flex flex-col items-center justify-evenly w-full h-full">
                    <!-- Botón superior en el marco negro -->
                    <button id="drawer-button" @click="drawerOpen = true" href=""
                        class="text-center font-bold text-xs md:text-xl text-neutral-400 uppercase border-2 border-black rounded-md py-1 px-4"
                        type="button" data-drawer-target="drawer-scenarios" data-drawer-show="drawer-scenarios"
                        aria-controls="drawer-scenarios">
                        ELEGIR ENTRENAMIENTO
                    </button>
                    <!-- Contenido de la pantalla (Texto dinámico) -->
                    <div class="w-[80%] rounded-lg p-2 bg-black text-white flex flex-col justify-center relative">
                        <div class="w-full aspect-[1.21] flex justify-center bg-white text-black text-2xl font-bold crt"
                            style="box-shadow: inset 0px 0px 3px 2px rgba(0,0,0,1);">
                            <img x-show="showImage" src="{{ asset('images/screen.png') }}" alt="Screen Content"
                                class="object-contain max-w-full max-h-full">
                            <div x-text="screen" class="text-base md:text-xl noto-sans-display"
                                :class="{ 'text-red-500': currentInstruction?.require_action, 'p-2': screen !== '' }"></div>
                            <div id="countdown-timer" class="font-mono text-xs"></div>
                        </div>
                    </div>
                    <div class="flex items-center justify-center w-[80%]">
                        <img src="{{ asset('images/laerdal.png') }}" alt="Laerdal Logo" class="w-[30%] object-contain cursor-pointer" @click="easterEggClick">
                    </div>
                </div>
            </div>
            <!-- Botón de descarga -->
            <button @click="logShockButtonPress" id="shock-button"
                class="w-24 h-24 md:w-32 md:h-32 flex items-center justify-center font-bold cursor-pointer mb-6 transform active:scale-90"
                :class="{ 'animate-pulse': currentInstruction?.require_action }"
                :disabled="!currentInstruction?.require_action">
                <img src="{{ asset('images/choque.svg') }}" alt="Shock Button" class="object-contain w-full h-full">
            </button>
        </div>

        <!-- Cajonera -->
        <div id="drawer-scenarios"
            class="fixed top-0 left-0 z-40 h-screen p-4 md:py-2 md:px-4 overflow-y-auto transition-transform -translate-x-full bg-neutral-50 w-2/3 md:w-1/3 lg:w-64 flex flex-col md:rounded-r-2xl shadow-xl"
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
            <div id="accordion-flush" data-accordion="collapse" data-active-classes="bg-transparent"
                class="overflow-y-auto flex-grow">
                <h2 id="accordion-flush-heading-1">
                    <button type="button"
                        class="flex items-center justify-between w-full mt-2 font-medium text-gray-500 rounded-t-xl"
                        data-accordion-target="#accordion-flush-body-1" aria-expanded="true"
                        aria-controls="accordion-flush-body-1">
                        <span class="text-xs uppercase font-semibold text-gray-500 line-clamp-1"><i
                                class="ri-aed-line text-sm font-normal me-2"></i>DEA DE ENTRENAMIENTO</span>
                        <i data-accordion-icon
                            class="ri-arrow-up-s-line text-gray-400 bg-transparent w-8 h-8 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-lg flex items-center justify-center"></i>
                    </button>
                </h2>
                <div id="accordion-flush-body-1" class="hidden" aria-labelledby="accordion-flush-heading-1">
                    <ul class="space-y-2 font-medium bg-white shadow-sm rounded-lg p-2 mb-2">
                        @foreach ($scenarios as $scenario)
                            @if ($scenario->scenario_id <= 8)
                                <li style="cursor: pointer;">
                                    <a class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group w-full"
                                        @click="selectScenario({{ $scenario->scenario_id }})">
                                        <img src="{{ asset($scenario->image_url) }}"
                                            alt="Escenario {{ $loop->iteration }}" class="w-auto h-8">
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                <h2 id="accordion-flush-heading-2">
                    <button type="button" class="flex items-center justify-between w-full mt-2 font-medium text-gray-500"
                        data-accordion-target="#accordion-flush-body-2" aria-expanded="false"
                        aria-controls="accordion-flush-body-2">
                        <span class="text-xs uppercase font-semibold text-gray-500 line-clamp-1"><i
                                class="ri-sd-card-line text-sm font-normal me-2"></i></i>PERSONALIZADOS</span>
                        <i data-accordion-icon
                            class="ri-arrow-up-s-line text-gray-400 bg-transparent w-8 h-8 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-lg flex items-center justify-center"></i>
                    </button>
                </h2>
                <div id="accordion-flush-body-2" class="hidden" aria-labelledby="accordion-flush-heading-2">
                    <ul class="space-y-2 font-medium bg-white shadow-sm rounded-lg p-2 mb-2">
                        @php
                            $personalizedScenarios = $scenarios->filter(function ($scenario) {
                                return $scenario->scenario_id > 8;
                            });
                        @endphp
                        @if ($personalizedScenarios->isEmpty())
                            <li class="text-center text-gray-500">¯\_(ツ)_/¯</li>
                        @else
                            @foreach ($personalizedScenarios as $scenario)
                                <li style="cursor: pointer;">
                                    <a class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group w-full"
                                        @click="selectScenario({{ $scenario->scenario_id }})">
                                        <img src="{{ asset($scenario->image_url) }}"
                                            alt="Escenario {{ $loop->iteration }}" class="w-auto h-8">
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
            <!-- Interruptor Metrónomo -->
            <label class="inline-flex items-center justify-between gap-3 my-2 cursor-pointer w-full">
                <div class="flex-1">
                    <h2 class="text-sm font-semibold text-gray-500">Metrónomo</h2>
                    <p class="text-xs text-gray-500">
                        El metrónomo está configurado para realizar 
                        <span class="font-bold">{{ session('metronome_bpm', 110) }}</span> 
                        compresiones por minuto.
                    </p>
                </div>
                <input type="checkbox" x-model="isMetronomeEnabled" class="sr-only peer" checked>
                <div
                    class="relative w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-600">
                </div>
            </label>
            <hr class="h-px my-2 bg-gray-200 border-0">
            <!-- Botón de cerrar sesión -->
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="my-2">
                @csrf
                <button type="submit"
                    class="text-gray-900 bg-white border border-gray-300 w-full focus:outline-none hover:bg-gray-100 focus:ring-gray-100 font-medium rounded text-sm px-5 py-2.5">
                    <i class="ri-logout-box-line me-2"></i>Finalizar sesión
                </button>
            </form>
        </div>
    </div>
