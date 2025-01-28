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
    <div id="contenedor-maniqui" class="relative mx-auto w-[300px] h-[500px] border">
        <!-- Imagen del maniquí -->
        <img 
            src="{{ asset('images/maniqui.png') }}" 
            alt="Maniquí" 
            class="w-full h-full"
        >

        <!-- Zonas de colocación -->
        <div id="zona-1" class="absolute top-1/3 left-1/4 w-8 h-8 border-2 border-dashed border-green-500"></div>
        <div id="zona-2" class="absolute top-1/3 right-1/4 w-8 h-8 border-2 border-dashed border-green-500"></div>
    </div>

    <!-- Electrodos arrastrables -->
    <div class="flex justify-center gap-4 mt-4">
        <div 
            id="electrodo-1" 
            class="electrodo w-16 h-16 bg-red-500 text-white text-center leading-[4rem] rounded-full cursor-move"
        >
            Electrodo 1
        </div>
        <div 
            id="electrodo-2" 
            class="electrodo w-16 h-16 bg-blue-500 text-white text-center leading-[4rem] rounded-full cursor-move"
        >
            Electrodo 2
        </div>
    </div>
</div>