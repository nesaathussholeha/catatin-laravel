import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // Umum
                'resources/css/app.css',
                'resources/js/app.js',

                // Authentication
                'resources/css/auth.css',
                'resources/js/auth.js',

                // Layout User
                'resources/css/user-layout.css',
                'resources/js/user-layout.js',

                // Dashboard User
                'resources/css/dashboard-user.css',
                'resources/js/dashboard-user.js',

                // Detail Catatan
                'resources/css/detail-catatan.css',
                'resources/js/detail-catatan.js',

                // Playlist
                'resources/css/playlist.css',
                'resources/js/playlist.js',

                // Layout Admin
                'resources/css/admin-layout.css',
                'resources/js/admin-layout.js',

                // Dashboard Admin
                'resources/css/dashboard-admin.css',
                'resources/js/dashboard-admin.js',

                'resources/css/tugas.css',
                'resources/js/tugas.js',

                // Kelola Catatan
                'resources/css/kelola-catatan.css',
                'resources/js/kelola-catatan.js',

                // Kelola Laporan
                'resources/css/kelola-laporan.css',
                'resources/js/kelola-laporan.js',

                // Catatan Saya
                'resources/css/catatan-saya.css',
                'resources/js/catatan-saya.js',

                // Unggah Catatan
                'resources/css/unggah-catatan.css',
                'resources/js/unggah-catatan.js',
            ],

            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});