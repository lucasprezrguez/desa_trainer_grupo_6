document.addEventListener("DOMContentLoaded", () => {
    let selectedElement = null; // Electrodo seleccionado
    let offsetX = 0, offsetY = 0; // Desplazamiento inicial

    // Detectar clic en un electrodo
    document.querySelectorAll(".electrodo").forEach(electrodo => {
        electrodo.addEventListener("mousedown", (event) => {
            selectedElement = event.target;
            offsetX = event.offsetX;
            offsetY = event.offsetY;
            selectedElement.style.cursor = "grabbing";
        });
    });

    // Mover el electrodo en tiempo real
    document.addEventListener("mousemove", (event) => {
        if (selectedElement) {
            const contenedorManiqui = document.getElementById("contenedor-maniqui").getBoundingClientRect();

            // Restricciones para que el electrodo no se salga del maniquí
            const x = Math.max(
                0, 
                Math.min(event.clientX - contenedorManiqui.left - offsetX, contenedorManiqui.width - selectedElement.offsetWidth)
            );
            const y = Math.max(
                0, 
                Math.min(event.clientY - contenedorManiqui.top - offsetY, contenedorManiqui.height - selectedElement.offsetHeight)
            );

            selectedElement.style.left = `${x}px`;
            selectedElement.style.top = `${y}px`;
        }
    });

    // Soltar el electrodo
    document.addEventListener("mouseup", () => {
        if (selectedElement) {
            selectedElement.style.cursor = "grab";
            selectedElement = null;
        }
    });
});
