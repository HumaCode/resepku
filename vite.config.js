import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/auth/login.css',
                'resources/js/auth/login.js',
                'resources/css/backend/resepkita-alert.css',
                'resources/js/backend/resepkita-alert.js',
                'resources/css/backend/dashboard.css',
                'resources/js/backend/dashboard.js',
                'resources/css/backend/global.css',
                'resources/js/backend/global.js',
                'resources/css/backend/role-permission.css',
                'resources/js/backend/role-permission.js',
                'resources/css/backend/kategori.css',
                'resources/js/backend/kategori.js',
                'resources/css/backend/tag.css',
                'resources/js/backend/tag.js',
                'resources/css/backend/ingredient.css',
                'resources/js/backend/ingredient.js',
                'resources/css/backend/permission.css',
                'resources/js/backend/permission.js',
            ],
            refresh: true,
        }),
    ],
});
