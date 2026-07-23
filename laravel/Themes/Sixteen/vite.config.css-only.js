import { defineConfig } from 'vite';
import laravel, { refreshPaths } from 'laravel-vite-plugin'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                //'resources/js/app.js',
            ],
            refresh: [
                ...refreshPaths,
                'app/Livewire/**',
            ],
        }),
        tailwindcss(),
    ],
    build: {
        outDir: './public',
        emptyOutDir: true,
        manifest: 'manifest.json',
        chunkFileNames: 'js/[name]-[hash].js',
        entryFileNames: 'js/[name]-[hash].js',
        assetFileNames: (assetInfo) => {
            const ext = assetInfo.name.split('.').pop();
            if (ext === 'css') return 'css/[name]-[hash].[ext]';
            if (['png','jpg','jpeg','gif','svg','webp','ico'].includes(ext)) return 'images/[name]-[hash].[ext]';
            if (['woff','woff2','eot','ttf','otf'].includes(ext)) return 'fonts/[name]-[hash].[ext]';
            return 'assets/[name]-[hash].[ext]';
        },
        minify: 'esbuild',
        sourcemap: false,
        target: 'es2020',
        cssCodeSplit: true,
        assetsInlineLimit: 4096
    },
    server: {
        hmr: { host: 'localhost' }
    },
});
