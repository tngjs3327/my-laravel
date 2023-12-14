import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/quill.css',
                
                'resources/js/app.js',
                'resources/js/createQuill.js',
                'resources/js/showQuill.js',
                'resources/js/updateQuill.js',

            ],
            refresh: true,
        }),
    ],
});
