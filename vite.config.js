import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
 
export default defineConfig({
    plugins: [
        laravel({
            // include both CSS and JS so Laravel's @vite(['resources/css/app.css', ...]) can find the files
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue(),
    ],
    resolve: {
        alias: {
            'vue': 'vue/dist/vue.esm-bundler.js', // ← tambahkan ini
        },
    },
});