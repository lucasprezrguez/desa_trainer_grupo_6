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
                     class="w-28 h-28 bg-green-500 rounded-full border-4 border-white cursor-pointer flex items-center justify-center text-white font-bold text-lg active:scale-95 active:text-base">
                    INICIO
                </div>
            </div>

            <!-- Pantalla con texto dinámico -->
            <div class="absolute top-1/3 left-1/2 transform -translate-x-1/2 bg-white w-64 h-36 flex items-center justify-center shadow-md mt-24 ml-40">
                <span id="mensajePantalla" class="text-black font-medium">PANTALLA</span>
            </div>

            <!-- Botón rojo -->
            <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 mb-[27%] ml-40">
                <img id="boton-descarga" src="{{ asset('images/boton-descarga-apagado.png') }}" alt="Botón de descarga" class="w-32 h-32 cursor-pointer active:scale-95">
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

    <!-- Botón para abrir la cajonera -->
    <button id="drawer-button" class="absolute top-4 right-4 bg-gray-500 text-white p-2 rounded-full" type="button" data-drawer-target="drawer-scenarios" data-drawer-show="drawer-scenarios" aria-controls="drawer-scenarios">
        <i class="ri-menu-line"></i>
    </button>

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
                @if(isset($scenarios))
                    @foreach($scenarios as $scenario)
                        <li>
                            <a href="#" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100 group">
                                <i class="ri-number-{{ $loop->iteration }} text-gray-500"></i>
                                <img src="{{ asset($scenario->image_url) }}" alt="Escenario {{ $loop->iteration }}" class="w-auto h-8 ms-3">
                            </a>
                        </li>
                    @endforeach
                @else
                    <li class="text-gray-500">No hay escenarios disponibles.</li>
                @endif
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

    <!-- Botón de prueba para animación de descarga -->
    <button id="test-button" class="absolute top-4 left-4 bg-blue-500 text-white p-2 rounded-full">
        Test Descarga
    </button>

    <script>
        document.getElementById("test-button").addEventListener("click", function () {
            const botonDescarga = document.getElementById("boton-descarga");
            botonDescarga.src = "{{ asset('images/boton-descarga-animacion.gif') }}";
        });
    </script>
</body>

