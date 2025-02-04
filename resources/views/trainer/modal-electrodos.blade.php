<div 
    id="modal-electrodo" 
    class="bg-white p-6 rounded-lg w-3/4 h-3/4 fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50 shadow-lg"
>
    <!-- Botón para cerrar -->
    <button 
        onclick="cerrarModal()" 
        class="absolute top-2 right-2 text-red-500 text-2xl font-bold"
    >
        &times;
    </button>

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

<script>
    document.addEventListener("DOMContentLoaded", () => {
        inicializarDragAndDrop();
    });

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
            const rangoInferior = 900; // Mínimo Y donde los aceptamos

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
            } else {
                botonContinuar.disabled = true;
                botonContinuar.classList.add("bg-gray-500", "opacity-50", "cursor-not-allowed");
                botonContinuar.classList.remove("bg-green-500", "hover:bg-green-600", "cursor-pointer");
            }
        }
    }
</script>



