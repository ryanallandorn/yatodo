import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { svelte } from '@sveltejs/vite-plugin-svelte';
import path from 'path';
import viteCompression from 'vite-plugin-compression';

const ASSET_URL = process.env.ASSET_URL || '';

// https://vitejs.dev/config/
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/scss/app.scss', 'resources/js/app.js'],
            refresh: true,
        }),
        svelte({
            experimental: {
                prebundleSvelteLibraries: true,
            },
        }),
        // viteCompression({ algorithm: 'brotliCompress', ext: '.br' }), // PROD
        
    ],
    resolve: {
		alias: {
            '@components': path.resolve(__dirname, 'resources/js/Components'),
            '@layouts': path.resolve(__dirname, 'resources/js/Layouts'),
            '@pages': path.resolve(__dirname, 'resources/js/Pages'),
            '@utils': path.resolve(__dirname, 'resources/js/Utils'),
            '@lang': path.resolve(__dirname, 'resources/js/Localization'),
            '@scripts': path.resolve(__dirname, 'resources/js/Scripts'),
		}
	},
    // optimizeDeps: {  // PROD
    //     include: [
    //         '@inertiajs/inertia',
    //         '@inertiajs/inertia-svelte',
    //     ]
    // }
});
