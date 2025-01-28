@extends('layouts.app')

<body class="w-screen h-screen bg-gray-200 flex items-center justify-center">
    <div class="bg-gray-400 w-full h-full flex items-center justify-center">
        <!-- Interior azul con border-radius personalizado -->
        <div class="bg-blue-900 w-11/12 aspect-[3/4] rounded-r-3xl p-4 relative">              
             <!-- Botón verde -->
            <div class="absolute top-6 left-1/2 transform -translate-x-1/2 mt-20 ml-40">
                <div class="w-28 h-28 bg-green-500 rounded-full border-4 border-white"></div>
            </div>
            <!-- Pantalla -->
            <div class="absolute top-1/3 left-1/2 transform -translate-x-1/2 bg-white w-64 h-36 flex items-center justify-center shadow-md mt-24 ml-40" >
                <span class="text-black font-medium">PANTALLA</span>
            </div>
            <!-- Botón rojo -->
            <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 mb-20 ml-40">
                <div class="w-32 h-32 bg-red-500 clip-triangle"></div>
            </div>
        </div>
    </div>

    <!-- Botón NUEVO para abrir el modal -->
    <button 
        onclick="abrirModalElectrodos()" 
        class="absolute top-6 right-6 bg-yellow-400 p-2 rounded-lg hover:bg-yellow-500 transition"
    >
        Colocar electrodos
    </button>

    <!-- Modal (añadido aquí) -->
    <div id="modalElectrodos" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <!-- Contenido del modal se cargará aquí -->
    </div>

    <style>
        .clip-triangle {
            clip-path: polygon(50% 0%, 0% 100%, 100% 100%);
        }
    </style>

    <!-- jQuery y jQuery UI -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <script>
        // Abrir modal
        function abrirModalElectrodos() {
            const modal = document.getElementById('modalElectrodos');
            
            // Verificar si el modal existe
            if (!modal) {
                console.error('No se encontró el modal');
                return;
            }

            modal.classList.remove('hidden');
            
            // Cargar contenido del modal via AJAX
            fetch("{{ route('ruta.modal.electrodos') }}")  <!-- Define tu ruta en web.php -->
                .then(response => response.text())
                .then(html => {
                    modal.innerHTML = html;
                    inicializarDragAndDrop(); // Inicializar drag and drop después de cargar el contenido
                })
                .catch(error => console.error('Error al cargar el modal:', error));
        }

        // Cerrar modal
        function cerrarModal() {
            const modal = document.getElementById('modalElectrodos');
            if (modal) {
                modal.classList.add('hidden');
            }
        }

        // Función para inicializar el drag and drop con jQuery UI
        function inicializarDragAndDrop() {
            $(function() {
                // Hacer los electrodos arrastrables
                $(".electrodo").draggable({
                    containment: "#contenedor-maniqui", // Restringe el movimiento dentro del contenedor
                    cursor: "move", // Cambia el cursor al mover
                    revert: "invalid", // Vuelve a su posición si no se suelta en una zona válida
                    stack: ".electrodo", // Asegura que el electrodo arrastrado esté siempre encima
                    touch: true // Soporte para dispositivos táctiles
                });

                // Zonas de colocación (opcional)
                $("#zona-1, #zona-2").droppable({
                    accept: ".electrodo",
                    drop: function(event, ui) {
                        alert("Electrodo colocado en " + $(this).attr("id"));
                    }
                });
            });
        }
    </script>
</body>