import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite'
import fs from 'fs';

export default defineConfig({
    server: {
        https: {
            key: fs.readFileSync('/etc/ssl/certs/laravel12.local-key.pem'),
            cert: fs.readFileSync('/etc/ssl/certs/laravel12.local.pem'),
        },
        host: '0.0.0.0',
        port: 5173,
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                ...glob.sync('resources/js/pages/**/*.js'),
                ...glob.sync('resources/js/libraries/**/*.js'),
            ],
            refresh: true,
            hotFile: 'public/hot',
        }),tailwindcss(),
    ],
});