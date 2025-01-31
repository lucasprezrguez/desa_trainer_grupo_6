@extends('layouts.app')

<body class="w-screen h-screen flex items-center justify-center">
    <div class="w-full h-full flex items-center justify-center relative">
        <!-- Imagen de fondo ocupando toda la pantalla -->
        <div class="absolute inset-0 w-full h-full bg-cover bg-center z-0" 
            style="background-image: url('{{ asset('images/desa-xrf.png') }}');">
        </div>

        <!-- Contenedor principal con contenido encima de la imagen -->
        <div class="w-11/12 h-full rounded-r-3xl p-4 relative z-10">            
            <!-- Botón verde (Inicio) -->
            <div class="absolute top-6 left-1/2 transform -translate-x-1/2 mt-[25%] ml-40">
                <div id="botonInicio" 
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
    <div id="modalElectrodos" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <!-- Contenido del modal se cargará aquí -->
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

            modal.classList.remove('hidden');

            fetch("{{ route('ruta.modal.electrodos') }}")
                .then(response => response.text())
                .then(html => {
                    modal.innerHTML = html;
                    inicializarDragAndDrop();
                })
                .catch(error => console.error('Error al cargar el modal:', error));
        }

        function cerrarModal() {
            const modal = document.getElementById('modalElectrodos');
            if (modal) {
                modal.classList.add('hidden');
            }
        }

        function inicializarDragAndDrop() {
            let selectedElement = null;
            let offsetX = 0, offsetY = 0;

            document.querySelectorAll(".electrodo").forEach(electrodo => {
                electrodo.addEventListener("touchstart", (event) => {
                    selectedElement = event.target;
                    const touch = event.touches[0];
                    const rect = selectedElement.getBoundingClientRect();

                    offsetX = touch.clientX - rect.left;
                    offsetY = touch.clientY - rect.top;

                    selectedElement.style.cursor = "grabbing";
                });

                electrodo.addEventListener("touchmove", (event) => {
                    if (selectedElement) {
                        const touch = event.touches[0];
                        const contenedorManiqui = document.getElementById("contenedor-maniqui").getBoundingClientRect();

                        const x = touch.clientX - contenedorManiqui.left - offsetX;
                        const y = touch.clientY - contenedorManiqui.top - offsetY;

                        const maxX = contenedorManiqui.width - selectedElement.offsetWidth;
                        const maxY = contenedorManiqui.height - selectedElement.offsetHeight;

                        selectedElement.style.left = `${Math.max(0, Math.min(x, maxX))}px`;
                        selectedElement.style.top = `${Math.max(0, Math.min(y, maxY))}px`;
                    }
                });

                electrodo.addEventListener("touchend", () => {
                    if (selectedElement) {
                        selectedElement.style.cursor = "grab";
                        selectedElement = null;
                    }
                });
            });
        }
    </script>
</body>

