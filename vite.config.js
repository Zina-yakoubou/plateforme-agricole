import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: true, // Écoute sur toutes les adresses locales (0.0.0.0)
        port: 5173,
        strictPort: true,
        hmr: {
            host: 'localhost',
        },
    },

    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});