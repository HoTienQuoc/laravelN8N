import fs from 'fs';
import path from 'path';
import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import { wayfinder } from '@laravel/vite-plugin-wayfinder';

const certPath = path.resolve(__dirname, 'docker/ssl/localhost.pem');
const keyPath = path.resolve(__dirname, 'docker/ssl/localhost.key');

export default defineConfig({
    server: {
        https: {
            cert: fs.readFileSync(certPath),
            key: fs.readFileSync(keyPath),
        },
        host: 'localhost',
        port: Number(process.env.VITE_PORT || 5173),
    },
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'),
        },
    },
    plugins: [
        laravel({
            input: ['resources/js/app.ts'],
            ssr: 'resources/js/ssr.ts',
            refresh: true,
        }),
        tailwindcss(),
        wayfinder({
            formVariants: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
});
