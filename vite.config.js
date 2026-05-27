import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/fonts.css', 'resources/css/app.css', 'resources/js/app.js', 'resources/js/copy-blocker.js'],
            refresh: true,
        }),
    ],
});
