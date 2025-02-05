@extends('layouts.app')

<body class="w-screen h-screen flex items-center justify-center">
    <div x-data="{
        isOn: false,
        backgroundImage: '{{ asset('images/desa-xrf.png') }}',
        togglePower() {
            this.isOn = !this.isOn;
            this.backgroundImage = this.isOn ? '{{ asset('images/device_pads1.png') }}' : '{{ asset('images/desa-xrf.png') }}';
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
        x-init="updateBackgroundSize()"
        @resize.window="updateBackgroundSize()"
        class="w-full h-full flex items-center justify-center relative"
    >
        <!-- Imagen de fondo ocupando toda la pantalla -->
        <img id="background-image" :src="backgroundImage" alt="Imagen de fondo" class="absolute inset-0 w-full h-full bg-cover bg-center z-0">

        <!-- Contenedor principal con contenido encima de la imagen -->
        <div class="w-11/12 h-full rounded-r-3xl p-4 relative z-10">            
            <!-- Botón verde (Inicio) -->
            <div class="absolute top-6 left-1/2 transform -translate-x-1/2 mt-[25%] ml-40">
                <div id="botonInicio" 
                     @click="togglePower"
                     class="w-28 h-28 bg-green-500 rounded-full border-4 border-white cursor-pointer flex items-center justify-center text-white font-bold text-lg">
                    INICIO
                </div>
            </div>

            <!-- Pantalla con texto dinámico -->
            <div class="absolute top-1/3 left-1/2 transform -translate-x-1/2 bg-white w-64 h-36 flex items-center justify-center shadow-md mt-24 ml-40">
                <span id="mensajePantalla" class="text-black font-medium">PANTALLA</span>
            </div>

            <!-- Botón rojo -->
            <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 mb-[27%] ml-40">
                <div class="w-32 h-32 bg-red-500 clip-triangle"></div>
            </div>
        </div>
    </div>

    <!-- Modal (se abrirá después de 5 segundos) -->
    <div id="modalElectrodos" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center" style="display: none;">
        <div 
            id="modal-electrodo" 
            class="bg-white p-6 rounded-lg w-3/4 h-3/4 fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50 shadow-lg"
        >
            <!-- Contenedor del Maniquí -->
            <div id="contenedor-maniqui" class="relative w-[300px] h-[500px] border">
                <!-- Imagen del maniquí -->
                <img 
                    src="{{ asset('images/maniqui.png') }}" 
                    alt="Maniquí" 
                    class="w-full h-full"
                >

                <!-- Zonas de colocación -->
                <div id="zona-1" class="zona-electrodo absolute top-1/3 left-1/4 w-8 h-8 border-2 border-dashed border-green-500"></div>
                <div id="zona-2" class="zona-electrodo absolute top-1/3 right-1/4 w-8 h-8 border-2 border-dashed border-green-500"></div>
            </div>

            <!-- Electrodos arrastrables -->
            <div class="flex justify-center gap-4 mt-4">
                <!-- Electrodo 1 -->
                <img 
                    id="electrodo-1" 
                    src="{{ asset('images/electrodo.png') }}" 
                    alt="Electrodo 1" 
                    class="electrodo w-16 h-16 cursor-move absolute"
                    style="top: 20px; left: 20px;"
                >
                <!-- Electrodo 2 -->
                <img 
                    id="electrodo-2" 
                    src="{{ asset('images/electrodo.png') }}" 
                    alt="Electrodo 2" 
                    class="electrodo w-16 h-16 cursor-move absolute"
                    style="top: 20px; left: 200px;"
                >
            </div>

            <!-- Botón de Continuar -->
            <button 
                id="botonContinuar" 
                class="mt-6 bg-gray-500 text-white py-2 px-4 rounded-lg opacity-50 cursor-not-allowed" 
                disabled
            >
                Continuar
            </button>
        </div>
    </div>
    
    <style>
        .clip-triangle {
            clip-path: polygon(50% 0%, 0% 100%, 100% 100%);
        }
    </style>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        document.getElementById("botonInicio").addEventListener("click", function () {
            // Cambia el mensaje de la pantalla
            const mensajePantalla = document.getElementById("mensajePantalla");
            mensajePantalla.textContent = "NECESARIO COLOCAR ELECTRODOS BIEN";
            mensajePantalla.style.display = 'flex';
            mensajePantalla.style.alignItems = 'center';
            mensajePantalla.style.justifyContent = 'center';
            mensajePantalla.style.height = '100%'; // Ensure the text is vertically centered
            mensajePantalla.style.marginLeft = '10px'; // Add left margin to simulate centering

            // Espera 5 segundos y luego abre el modal
            setTimeout(() => {
                abrirModalElectrodos();
            }, 2400);
        });

        function abrirModalElectrodos() {
            const modal = document.getElementById('modalElectrodos');

            if (!modal) {
                console.error('No se encontró el modal');
                return;
            }

            modal.style.display = 'flex';
            inicializarDragAndDrop();
        }

        function cerrarModal() {
            const modal = document.getElementById('modalElectrodos');
            if (modal) {
                modal.style.display = 'none';
            }
        }

        function inicializarDragAndDrop() {
            let selectedElement = null;
            let offsetX = 0, offsetY = 0;
            const zonasElectrodos = document.querySelectorAll(".zona-electrodo");
            const botonContinuar = document.getElementById("botonContinuar");
            let electrodoLockeado = { "electrodo-1": false, "electrodo-2": false };

            document.querySelectorAll(".electrodo").forEach(electrodo => {
                electrodo.addEventListener("touchstart", startDrag, { passive: false });
                electrodo.addEventListener("touchmove", drag, { passive: false });
                electrodo.addEventListener("touchend", stopDrag);
            });

            function startDrag(event) {
                event.preventDefault();
                selectedElement = event.target;

                if (electrodoLockeado[selectedElement.id]) {
                    selectedElement = null; // Si ya está bloqueado, no se mueve
                    return;
                }

                const touch = event.touches[0];
                const rect = selectedElement.getBoundingClientRect();

                offsetX = touch.clientX - rect.left;
                offsetY = touch.clientY - rect.top;
            }

            function drag(event) {
                if (!selectedElement) return;
                event.preventDefault();

                const touch = event.touches[0];
                const modal = document.getElementById("modal-electrodo").getBoundingClientRect();

                let x = touch.clientX - modal.left - offsetX;
                let y = touch.clientY - modal.top - offsetY;

                selectedElement.style.position = "absolute";
                selectedElement.style.left = `${x}px`;
                selectedElement.style.top = `${y}px`;
            }

            function stopDrag() {
                if (!selectedElement) return;
                verificarAlturaElectrodos();
                selectedElement = null;
            }

            function verificarAlturaElectrodos() {
                const rangoSuperior = 100; // Máximo Y donde los aceptamos
                const rangoInferior = 400; // Mínimo Y donde los aceptamos

                let lockeados = 0;

                document.querySelectorAll(".electrodo").forEach(electrodo => {
                    const rectElectrodo = electrodo.getBoundingClientRect();
                    const modal = document.getElementById("modal-electrodo").getBoundingClientRect();
                    const posicionY = rectElectrodo.top - modal.top;

                    if (posicionY >= rangoSuperior && posicionY <= rangoInferior) {
                        electrodo.style.border = "3px solid green";
                        electrodoLockeado[electrodo.id] = true;
                        lockeados++;
                    } else {
                        electrodo.style.border = "none";
                        electrodoLockeado[electrodo.id] = false;
                    }
                });

                if (lockeados >= 2) {
                    botonContinuar.disabled = false;
                    botonContinuar.classList.remove("bg-gray-500", "opacity-50", "cursor-not-allowed");
                    botonContinuar.classList.add("bg-green-500", "hover:bg-green-600", "cursor-pointer");
                    botonContinuar.addEventListener("click", cerrarModal);
                } else {
                    botonContinuar.disabled = true;
                    botonContinuar.classList.add("bg-gray-500", "opacity-50", "cursor-not-allowed");
                    botonContinuar.classList.remove("bg-green-500", "hover:bg-green-600", "cursor-pointer");
                    botonContinuar.removeEventListener("click", cerrarModal);
                }
            }
        }
    </script>
</body>

