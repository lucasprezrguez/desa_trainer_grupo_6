import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: false,
        }),
    ],
    // server: {
    //     host: '192.168.221.33',
    //     port: 3000,
    //     open: false,
    //     cors: {
    //         origin: 'http://192.168.221.33:8000',
    //         methods: ['GET', 'POST'],
    //     },
    // },
});
