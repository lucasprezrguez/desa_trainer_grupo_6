import './bootstrap';
import 'flowbite';


// document.addEventListener("DOMContentLoaded", () => {
//     let selectedElement = null; // Electrodo seleccionado
//     let offsetX = 0, offsetY = 0; // Desplazamiento inicial
//     console.log("AAA");
//     // Detectar clic en un electrodo
//     document.querySelectorAll(".electrodo").forEach(electrodo => {
//         electrodo.addEventListener("touchstart", (event) => {
//             selectedElement = event.target;
//             offsetX = event.offsetX;
//             offsetY = event.offsetY;
//             selectedElement.style.cursor = "grabbing";
//         });
//     });

//     // Mover el electrodo en tiempo real
//     document.addEventListener("touchmove", (event) => {
//         if (selectedElement) {
//             const contenedorManiqui = document.getElementById("contenedor-maniqui").getBoundingClientRect();

//             // Restricciones para que el electrodo no se salga del maniquí
//             const x = Math.max(
//                 0, 
//                 Math.min(event.clientX - contenedorManiqui.left - offsetX, contenedorManiqui.width - selectedElement.offsetWidth)
//             );
//             const y = Math.max(
//                 0, 
//                 Math.min(event.clientY - contenedorManiqui.top - offsetY, contenedorManiqui.height - selectedElement.offsetHeight)
//             );

//             selectedElement.style.left = `${x}px`;
//             selectedElement.style.top = `${y}px`;
//         }
//     });

//     // Soltar el electrodo
//     document.addEventListener("touchend", () => {
//         if (selectedElement) {
//             selectedElement.style.cursor = "grab";
//             selectedElement = null;
//         }
//     });
// });

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
