import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/partials/side-nav.css',
                'resources/css/pages/customers.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
