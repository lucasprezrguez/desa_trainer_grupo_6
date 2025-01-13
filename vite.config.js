import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        host: '192.168.221.160', // Cambia la IP aquí
        port: 3000,              // Puedes cambiar el puerto si es necesario
        open: false,             // No abrir automáticamente en el navegador
      },
});
