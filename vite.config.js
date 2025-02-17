import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: false,
        }),
    ],
    server: {
        host: 'localhost',
        port: 3000,
        open: false,
        cors: {
            origin: 'http://127.0.0.1:8000',
            methods: ['GET', 'POST'],
        },
    },
});
