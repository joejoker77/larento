import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import fs from 'fs';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/scss/app.scss',
                'resources/scss/fonts.scss',
                'resources/scss/admin.scss',
                'resources/js/app.js',
                'resources/js/admin.js'
            ],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0',
        port: 3000,
        open: false,
        hmr: {clientPort:3000,host: "localhost"},
        https: {
            key: fs.readFileSync('docker/nginx/ssl/ssl-cert-snakeoil.key'),
            cert: fs.readFileSync('docker/nginx/ssl/ssl-cert-snakeoil.pem')
        }
    },
});
