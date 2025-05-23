@extends('layouts.app')

@section('content')
    <style>
        [x-cloak] {
            display: none !important;
        }

        #aed-container {
            transition: visibility 50ms ease-out;
        }

        [drawer-backdrop],
        [modal-backdrop] {
            backdrop-filter: blur(5px);
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
        logCount: 0,
        scenarioInstruction: {{ $scenarioInstruction }},
        instructions: {{ $instructions }},
        currentInstruction: null,
        screen: '',
        showImage: true,
        hasAdditionalInfo: false,
        selectedScenarioId: 0,
        scenarioInstructionSelected: null,
        countdownInterval: null,
        isBackgroundLoaded: false,
        isMetronomeEnabled: true,
        easterEggClickCount: 0,
        isPaused: false,
        remainingTime: null,
        isFullscreen: false,
        timeoutId: null,
        showCountdown: false,
        showCounter: false,

        toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => {
                    console.error(`Error al intentar activar el modo de pantalla completa: ${err.message}`);
                }).then(() => {
                    this.isFullscreen = true;
                });
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen().then(() => {
                        this.isFullscreen = false;
                    });
                }
            }
        },
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
            this.showCountdown = false;
            this.showCounter = false;
            this.isPaused = false;

            clearInterval(this.countdownInterval);
            this.countdownInterval = null;

            if (this.timeoutId) {
                clearTimeout(this.timeoutId);
                this.timeoutId = null;
            }

            if (!confirm('¿Estás seguro de que quieres empezar la simulación?')) return;

            this.scenarioInstructionSelected = this.scenarioInstruction.filter(
                scenario => scenario.scenario_id == this.selectedScenarioId
            );

            if (!this.scenarioInstructionSelected.length) {
                return;
            }

            this.$refs.closeButton.click();

            this.stopMetronome();

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
            this.showCountdown = false;
            this.showCounter = false;

            if (!this.isOn) {
                clearInterval(this.countdownInterval);
                this.countdownInterval = null;

                if (this.timeoutId) {
                    clearTimeout(this.timeoutId);
                    this.timeoutId = null;
                }

                speechSynthesis.cancel();

                // Registrar la finalización del escenario cuando se apaga el dispositivo
                if (this.scenarioInstructionSelected && this.scenarioInstructionSelected.length > 0) {
                    fetch('{{ route('record.completion') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            scenario_id: this.selectedScenarioId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log('Progreso registrado exitosamente');
                        }
                    })
                    .catch(error => {
                        console.error('Error al registrar el progreso:', error);
                    });
                }

                this.scenarioInstructionSelected = null;
                this.currentInstruction = null;
                this.showImage = true;
                this.hasAdditionalInfo = false;
                
                if (this.showInfoModal) {
                    this.showInfoModal = false;
                }

                this.stopMetronome();
            } else {
                this.startLogging();
            }
        },
        logShockButtonPress() {
            if (!this.scenarioInstructionSelected || !this.scenarioInstructionSelected.length) {
                return;
            }

            if (this.logCount >= this.scenarioInstructionSelected.length) {
                this.logCount = 0;
                this.showImage = true;
                this.togglePower();
                return;
            }

            const currentScenarioInstruction = this.scenarioInstructionSelected[this.logCount];

            if (!currentScenarioInstruction) {
                return;
            }

            this.currentInstruction = this.instructions.find(
                instruction => parseInt(instruction.instruction_id) === parseInt(currentScenarioInstruction.instruction_id)
            );

            if (!this.currentInstruction) {
                return;
            }
            
            this.hasAdditionalInfo = this.currentInstruction.additional_info && this.currentInstruction.additional_info.trim() !== '';
            
            const utterance = new SpeechSynthesisUtterance(currentScenarioInstruction.params);
            speechSynthesis.cancel();

            if (this.timeoutId) {
                clearTimeout(this.timeoutId);
            }

            this.stopMetronome();

            if (this.currentInstruction.require_action) {
                this.showImage = false;
                this.screen = currentScenarioInstruction.params;
                this.showCountdown = false;
                this.showCounter = false;
                speechSynthesis.speak(utterance);
            } else {
                this.showImage = false;
                this.screen = currentScenarioInstruction.params;
                
                if (parseInt(this.currentInstruction.instruction_id) === 4) {
                    utterance.onend = () => {
                        if (this.isMetronomeEnabled) {
                            this.playMetronome();
                        }
                    };
                }
                
                speechSynthesis.speak(utterance);

                this.showCountdown = true;
                this.showCounter = true;

                this.startCountdown(this.currentInstruction.waiting_time);

                this.timeoutId = setTimeout(() => {
                    if (!this.isPaused) {
                        clearInterval(this.countdownInterval);
                        this.logCount += 1;
                        this.logShockButtonPress();
                    }
                }, this.currentInstruction.waiting_time * 1000);
            }

            if (parseInt(this.currentInstruction.instruction_id) === 4 && this.currentInstruction.require_action) {
                utterance.onend = () => {
                    if (this.isMetronomeEnabled) {
                        this.playMetronome();
                    }
                };
            }
        },
        previousInstruction() {
            if (this.logCount > 0) {
                this.logCount -= 1;
                if (this.isPaused) {
                    this.isPaused = false;
                }
                this.logShockButtonPress();
            }
        },
        togglePause() {
            this.isPaused = !this.isPaused;
            if (this.isPaused) {
                clearTimeout(this.timeoutId);
                clearInterval(this.countdownInterval);
                this.stopMetronome();
            } else {
                if (this.currentInstruction && !this.currentInstruction.require_action) {
                    this.startCountdown(this.remainingTime);
                    this.timeoutId = setTimeout(() => {
                        if (!this.isPaused) {
                            clearInterval(this.countdownInterval);
                            this.logCount += 1;
                            this.logShockButtonPress();
                        }
                    }, this.remainingTime * 1000);
                }
                if (parseInt(this.currentInstruction?.instruction_id) === 4 && this.isMetronomeEnabled) {
                    this.playMetronome();
                }
            }
        },
        nextInstruction() {
            if (this.logCount < (this.scenarioInstructionSelected?.length || 0) - 1) {
                this.logCount += 1;
                if (this.isPaused) {
                    this.isPaused = false;
                }
                this.logShockButtonPress();
            }
        },
        startCountdown(waitingTime) {
            this.remainingTime = waitingTime;

            clearInterval(this.countdownInterval);

            $('#countdown-timer').text(this.formatTime(this.remainingTime));
            this.countdownInterval = setInterval(() => {
                if (!this.isPaused) {
                    this.remainingTime -= 1;
                    $('#countdown-timer').text(this.formatTime(this.remainingTime));
                    if (this.remainingTime <= 0) {
                        clearInterval(this.countdownInterval);
                    }
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
            
            this.metronomeInterval = setInterval(
                metronomeSound, 
                60000 / this.metronomeBpm
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
        <!-- Botón de pantalla completa -->
        <button id="fullscreen-button" @click="toggleFullscreen" 
            class="fixed w-12 h-12 bottom-4 right-4 z-50 p-2.5 text-white bg-neutral-700 hover:bg-neutral-800 focus:ring-4 focus:ring-neutral-300 rounded-full shadow-lg transition-all duration-300 ease-in-out transform hover:scale-105 active:scale-95">
            <i :class="isFullscreen ? 'ri-fullscreen-exit-line' : 'ri-fullscreen-line'" class="text-xl"></i>
            <span class="sr-only">Pantalla completa</span>
        </button>
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
                        class="text-center font-bold text-xs md:text-xl text-neutral-400 uppercase border-2 border-black rounded-xl py-1 px-4"
                        type="button" data-drawer-target="drawer-scenarios" data-drawer-show="drawer-scenarios"
                        aria-controls="drawer-scenarios">
                        ELEGIR ENTRENAMIENTO
                    </button>
                    <!-- Contenido de la pantalla (Texto dinámico) -->
                    <div class="w-[80%] rounded-lg p-2 bg-black text-white flex flex-col justify-center relative">
                        <div class="w-full aspect-[1.21] flex justify-center items-center bg-white text-black text-2xl font-bold crt relative"
                            style="box-shadow: inset 0px 0px 3px 2px rgba(0,0,0,1);">
                            <img x-show="showImage" src="{{ asset('images/screen.png') }}" alt="Screen Content"
                                class="object-contain max-w-full max-h-full">
                            <div x-text="screen" class="text-base text-center md:text-xl font-figtree absolute inset-0 flex items-center justify-center"
                                :class="{ 'text-[#FF5218]': currentInstruction?.require_action, 'p-2': screen !== '' }"></div>
                            
                            <!-- Enlace "Más información" -->
                            <a 
                                x-show="hasAdditionalInfo" 
                                data-modal-target="additional-info-modal" 
                                data-modal-toggle="additional-info-modal" 
                                class="absolute bottom-10 text-center w-full text-blue-600 text-xs underline cursor-pointer hover:text-blue-800">
                                Más información
                            </a>
                            
                            <div id="countdown-timer" x-show="showCountdown" class="absolute bottom-2.5 right-2.5 px-2.5 py-1.5 rounded text-black text-xs font-mono before:content-['\f20e'] before:font-['remixicon'] before:mr-1 before:text-[10px] before:font-thin before:align-baseline"></div>
                            <div id="instruction-counter" x-show="showCounter" class="absolute bottom-2.5 left-2.5 px-2.5 py-1.5 rounded text-black text-xs font-mono" x-text="`${logCount + 1}/${scenarioInstructionSelected?.length || 0}`"></div>
                        </div>
                    </div>
                    <!-- Logo y controles multimedia -->
                    <div class="flex items-center justify-center w-[80%]">
                        <!-- Logo de Laerdal -->
                        <img x-show="!isOn || !scenarioInstructionSelected" src="{{ asset('images/laerdal.png') }}" alt="Laerdal Logo" class="w-[30%] object-contain cursor-pointer" @click="easterEggClick">

                        <!-- Controles multimedia -->
                        <div x-show="isOn && scenarioInstructionSelected" class="flex items-center justify-center w-full gap-2">
                            <!-- Botón de retroceder -->
                            <button @click="previousInstruction" :disabled="logCount === 0" class="w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center text-white font-bold cursor-pointer transform active:scale-90"
                                :class="{ 'opacity-50 cursor-not-allowed': logCount === 0 }">
                                <i class="ri-skip-back-mini-fill text-xl md:text-2xl"></i>
                            </button>
                            <!-- Botón de pausa/reanudar -->
                            <button @click="togglePause" class="bg-white w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center text-black font-bold cursor-pointer border-2 border-gray-700 transform active:scale-90">
                                <i :class="isPaused ? 'ri-play-fill' : 'ri-pause-fill'" class="text-xl md:text-2xl"></i>
                            </button>
                            <!-- Botón de avanzar -->
                            <button @click="nextInstruction" :disabled="logCount >= (scenarioInstructionSelected?.length || 0) - 1" class="w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center text-white font-bold cursor-pointer transform active:scale-90"
                                :class="{ 'opacity-50 cursor-not-allowed': logCount >= (scenarioInstructionSelected?.length || 0) - 1 }">
                                <i class="ri-skip-forward-mini-fill text-xl md:text-2xl"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Botón de descarga -->
            <button @click="() => { if (currentInstruction?.require_action) { logCount += 1; logShockButtonPress(); } }" id="shock-button"
                class="w-24 h-24 md:w-32 md:h-32 flex items-center justify-center font-bold cursor-pointer mb-6 transform active:scale-90"
                :class="{ 'animate-pulse drop-shadow-[0_0_8px_#fde68a]': currentInstruction?.require_action }"
                :disabled="!currentInstruction?.require_action">
                <svg version="1.0" xmlns="http://www.w3.org/2000/svg" class="w-full h-full" viewBox="0 0 512 512" preserveAspectRatio="xMidYMid meet">
                    <g class="fill-[#240000]" :class="{ 'stroke-amber-100 stroke-[8]': currentInstruction?.require_action, 'stroke-none stroke-0': !currentInstruction?.require_action }">
                        <path d="M241 510.4 c-1.4 -0.2 -7.4 -1.1 -13.5 -2 -46 -6.7 -94.7 -32.5 -142.1 -75.4 -16.3 -14.8 -46.1 -47.9 -50.5 -56.2 -0.8 -1.4 8.6 -17.8 45.9 -80.1 56.5 -94.2 141.2 -235.6 162 -270.5 8.3 -13.9 15.3 -24.9 15.5 -24.5 2.7 4.7 85.9 145.9 129.9 220.8 31.1 52.8 64 108.8 73.2 124.4 l16.7 28.4 -2.4 3.4 c-1.3 1.8 -6.3 8.1 -11.1 13.8 -55.1 66.3 -113 105.1 -172.6 115.7 -11 1.9 -44.3 3.4 -51 2.2z m43.5 -13 c36.9 -5.4 74.9 -23.8 112.7 -54.6 18.2 -14.9 49.3 -46.9 63.1 -64.9 l2.7 -3.6 -74.1 -125.9 c-40.7 -69.2 -86.4 -146.8 -101.5 -172.4 -15 -25.6 -27.9 -47.3 -28.6 -48.2 -1.1 -1.6 -13.9 19.2 -105.3 171.9 -57.2 95.5 -104.2 174 -104.3 174.5 -0.6 1.4 18.5 23.5 32.8 37.8 52.5 52.7 108.7 82.3 166 87.4 7.1 0.6 25.6 -0.4 36.5 -2z"/>
                    </g>
                    <g fill="#ff3e19">
                        <path d="M245 502.4 c-48.4 -4.4 -94.3 -25.3 -140.5 -63.9 -19.2 -16 -44.4 -42 -57.9 -59.8 l-2.9 -3.7 15.9 -26.5 c8.7 -14.6 16.2 -26.5 16.6 -26.5 1.4 0 0.8 5.9 -0.9 9.5 l-1.7 3.5 4.9 9.6 c29.5 58.4 86.2 98.3 152.9 107.9 37.7 5.4 77.1 -2.1 115.3 -22 35.3 -18.4 63.9 -47 83.9 -84 4.5 -8.4 4.8 -9.3 4.6 -15.8 -0.3 -6.1 -0.1 -6.8 1.5 -6.5 1.1 0.2 7.4 9.8 16.6 25.5 l14.9 25.3 -8.8 10.7 c-54.8 66.5 -114.2 106.1 -172.3 114.8 -10.6 1.6 -34 2.6 -42.1 1.9z"/>
                        <path d="M209.8 432.3 c-1.3 -0.3 -1.8 -1.5 -1.8 -3.9 0 -1.9 0.5 -3.4 1 -3.4 0.6 0 1 -0.9 1 -2 0 -1.6 0.7 -2 3.4 -2 3.1 0 3.4 0.3 4 3.8 1.2 7.4 0.9 8.2 -2.6 8.1 -1.8 -0.1 -4.1 -0.3 -5 -0.6z"/>
                        <path d="M428 315.4 c0 -4.5 2.5 -4.4 5.3 0.4 l1.9 3.2 -3.6 0 c-3.5 0 -3.6 -0.1 -3.6 -3.6z"/>
                        <path d="M230 279.5 c0 -3.3 0.2 -3.5 3.5 -3.5 3.3 0 3.5 0.2 3.5 3.5 0 3.3 -0.2 3.5 -3.5 3.5 -3.3 0 -3.5 -0.2 -3.5 -3.5z"/>
                        <path d="M286 73 c0 -1.3 -0.7 -2 -2 -2 -1.3 0 -2 -0.7 -2 -2 0 -1.1 -0.7 -2 -1.5 -2 -0.8 0 -1.5 -0.7 -1.5 -1.5 0 -0.8 -0.7 -1.5 -1.5 -1.5 -0.9 0 -1.5 -0.9 -1.5 -2.5 0 -2 -0.6 -2.5 -3.2 -2.9 -8.5 -1.3 -17.7 -1.7 -25.5 -1.1 -8.3 0.7 -8.6 0.8 -10.5 4.1 -1.4 2.4 -2.7 3.4 -4.4 3.4 -1.3 0 -2.4 -0.3 -2.4 -0.6 0 -0.3 6.2 -10.9 13.7 -23.5 10.4 -17.3 14 -22.6 14.9 -21.6 1.3 1.5 32.4 54.3 32.4 55.1 0 0.3 -1.1 0.6 -2.5 0.6 -1.8 0 -2.5 -0.5 -2.5 -2z"/>
                    </g>
                    <g fill="#ff5218">
                        <path d="M241 457 c-64.2 -5.6 -119.8 -38 -154.1 -90 -3.9 -5.7 -9.5 -15.5 -12.5 -21.6 l-5.5 -11.2 3.2 -5.9 c4.9 -8.9 157.2 -263.1 160.8 -268.5 l3.1 -4.6 9.6 -0.7 c8.9 -0.7 23.7 -0.1 31.1 1.1 2.2 0.4 3 1 2.6 2 -0.3 0.8 0.1 1.4 1 1.4 0.8 0 2 0.7 2.7 1.5 0.9 1 0.9 1.5 0.1 1.5 -0.6 0 -1.1 0.5 -1.1 1 0 0.6 0.6 1 1.4 1 2.1 0 4 2.7 2.4 3.3 -0.9 0.4 -0.9 0.6 0.2 0.6 0.8 0.1 5.2 6.3 9.6 13.9 4.5 7.5 28.5 48.3 53.4 90.7 24.9 42.4 53.9 91.6 64.5 109.5 12.3 20.8 18.8 32.8 18.1 33.2 -0.7 0.5 -0.5 0.8 0.4 0.8 0.9 0 3 2.5 4.7 5.5 1.8 3 2.8 5.5 2.3 5.5 -0.5 0 -0.8 0.9 -0.7 2 0.1 1.2 0.8 2 1.9 2 1.8 0 3.3 1.6 2 2.2 -0.4 0.1 -3.4 5.6 -6.7 12.1 -24.3 49.1 -66.3 85.2 -119.6 102.6 -21.6 7.1 -53.3 11 -74.9 9.1z m9.6 -63.8 l-9.6 -0.3 0 -3 c0 -2 3.5 -9.5 9.9 -21.3 32.6 -59.8 51.3 -94.8 50.9 -95.1 -0.2 -0.3 -15 1.8 -32.8 4.6 -17.8 2.7 -33.8 4.9 -35.4 4.7 -2.7 -0.3 -3.1 -0.8 -3.4 -3.4 -0.3 -2.8 4.3 -9.2 38.4 -54 21.3 -28 39.1 -51.5 39.7 -52.1 0.7 -1 -3.8 -1.3 -21.9 -1.3 l-22.9 0 -6.7 15.3 c-3.7 8.3 -17.7 39.8 -31.2 70 l-24.4 54.7 5.6 0 c3.2 0 14.7 -0.7 25.7 -1.5 11 -0.8 20.8 -1.5 21.8 -1.5 3.2 0 1.9 6.9 -9 45.8 l-10.7 38.2 -11.6 0 -11.6 0 2.8 15.8 c1.5 8.6 3 16.7 3.3 17.9 0.5 2 2.8 0.3 21.6 -15.5 l21 -17.7 -9.5 -0.3z"/>
                    </g>
                    <g fill="#ffffff">
                        <path d="M215.6 434.8 c-0.3 -0.7 -2.1 -10.3 -4.1 -21.3 -2 -11 -3.8 -20.8 -4.1 -21.7 -0.5 -1.7 0.5 -1.8 11.8 -1.8 l12.4 0 10.8 -38.6 c5.9 -21.2 10.7 -38.6 10.5 -38.8 -0.2 -0.2 -8.9 0.3 -19.4 1 -10.4 0.7 -23.4 1.5 -28.7 1.9 l-9.8 0.7 4.4 -9.9 c2.4 -5.4 13.9 -31.2 25.6 -57.3 11.6 -26.1 24.4 -54.8 28.3 -63.7 l7.1 -16.2 27.9 -0.3 c15.3 -0.2 28 -0.2 28.2 0 0.1 0.2 -18.6 25.1 -41.7 55.4 -23 30.3 -41.7 55.2 -41.5 55.5 0.3 0.2 17.4 -2.3 38.1 -5.5 20.7 -3.2 37.6 -5.5 37.6 -5 0 0.6 -28 52.6 -55.6 103.3 l-9.3 17 13.1 0.5 13 0.5 -24.3 20.5 c-13.4 11.3 -25.6 21.6 -27.1 22.8 -2.1 1.8 -2.9 2 -3.2 1z"/>
                    </g>
                </svg>
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
                        <span class="underline decoration-dotted">{{ session('metronome_bpm', 110) }}</span> 
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

        <!-- Modal de información adicional con Flowbite -->
        <div id="additional-info-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <!-- Modal backdrop -->
            <div modal-backdrop class="fixed inset-0 bg-black/30"></div>
            <div class="relative p-4 w-full max-w-lg max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow-sm">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900">
                            <span x-text="currentInstruction?.instruction_name"></span>
                        </h3>
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="additional-info-modal">
                            <i class="ri-close-line text-xl"></i>
                            <span class="sr-only">Cerrar modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-4 md:p-5 space-y-4">
                        <div class="prose prose-sm" x-html="currentInstruction?.additional_info"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
