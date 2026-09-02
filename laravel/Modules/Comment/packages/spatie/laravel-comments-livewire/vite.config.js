import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'

export default defineConfig({
    build: {
        sourcemap: false,
        manifest: false,
        rollupOptions: {
            output: {
                entryFileNames: `[name].js`,
                chunkFileNames: `[name].js`,
                assetFileNames: `[name].[ext]`
            },
        },
    },
    plugins: [
        laravel({
            input: [
                // 'node_modules/easymde/dist/easymde.min.js',
                'resources/js/comments.js',
                'resources/css/comments.css',
            ],
            refresh: true,
            publicDirectory: 'resources',
            buildDirectory: 'dist',
        }),
    ],
})
