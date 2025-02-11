import './bootstrap';
import 'flowbite';

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
