import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/side-nav.css',
                'resources/css/customers.css',
                'resources/js/app.js',
                'resources/js/customers.js',
                'resources/js/generate-reports.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
