import { defineConfig } from 'vite';
import laravel, { refreshPaths } from 'laravel-vite-plugin'
import tailwindcss from '@tailwindcss/vite'
import nodeResolve from '@rollup/plugin-node-resolve';
import path from 'path';
import fs from 'fs';

export default defineConfig({
    base: '/themes/Sixteen/',
    resolve: {
        alias: {
            '@modules': path.resolve(__dirname, '../../Modules'),
            lit: path.resolve(__dirname, 'node_modules/lit'),
            leaflet: path.resolve(__dirname, 'node_modules/leaflet'),
            'leaflet.markercluster': path.resolve(__dirname, 'node_modules/leaflet.markercluster'),
            'leaflet.heat': path.resolve(__dirname, 'node_modules/leaflet.heat'),
            '@theme-lit': path.resolve(__dirname, 'node_modules/lit/index.js'),
            '@theme-leaflet': path.resolve(__dirname, 'node_modules/leaflet/dist/leaflet-src.js'),
            '@theme-leaflet-css': path.resolve(__dirname, 'node_modules/leaflet/dist/leaflet.css'),
        },
    },
    optimizeDeps: {
        include: ['leaflet', 'lit'],
    },
    plugins: [
        laravel({
            publicDirectory: '../../../public_html',
            input: [
                'resources/css/app.css',
                'resources/css/app-test.css',
                'resources/js/app.js',
                '../../Modules/Geo/resources/js/components/map-lit.js',
                'node_modules/leaflet.markercluster/dist/leaflet.markercluster.js',
            ],
            refresh: [
                ...refreshPaths,
                'app/Livewire/**',
            ],
        }),
        tailwindcss(),
        {
            name: 'sync-manifest',
            closeBundle() {
                const src = path.resolve(__dirname, '../../../public_html/themes/Sixteen/manifest.json');
                const dst = path.resolve(__dirname, 'public/manifest.json');
                try {
                    if (fs.existsSync(src)) {
                        fs.mkdirSync(path.dirname(dst), { recursive: true });
                        fs.copyFileSync(src, dst);
                    }
                } catch { /* silent */ }
            },
        },
        {
            name: 'copy-design-comuni-assets',
            closeBundle() {
                const outBase = path.resolve(__dirname, '../../../public_html/themes/Sixteen/design-comuni');
                const copies = [
                    {
                        src: path.resolve(__dirname, 'node_modules/bootstrap-italia/dist/svg/sprites.svg'),
                        dst: path.join(outBase, 'assets/bootstrap-italia/dist/svg/sprites.svg'),
                    },
                    {
                        src: path.resolve(__dirname, 'Main_files/five/assets/images/logo-eu-inverted.svg'),
                        dst: path.join(outBase, 'assets/images/logo-eu-inverted.svg'),
                    },
                    {
                        src: path.resolve(__dirname, 'Main_files/five/assets/images/logo-comune.svg'),
                        dst: path.join(outBase, 'assets/images/logo-comune.svg'),
                    },
                ];
                for (const { src, dst } of copies) {
                    try {
                        if (fs.existsSync(src)) {
                            fs.mkdirSync(path.dirname(dst), { recursive: true });
                            fs.copyFileSync(src, dst);
                        }
                    } catch { /* silent */ }
                }
            },
        },
    ],
    build: {
        outDir: '../../../public_html/themes/Sixteen',
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
        assetsInlineLimit: 4096,
        rollupOptions: {
            plugins: [
                nodeResolve({
                    browser: true,
                    preferBuiltins: false,
                    extensions: ['.mjs', '.js', '.json', '.node', '.css'],
                }),
            ],
        },
    },
    server: {
        hmr: { host: 'localhost' }
    },
});
