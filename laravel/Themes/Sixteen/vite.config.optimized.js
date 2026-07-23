import { defineConfig } from 'vite';
import laravel, { refreshPaths } from 'laravel-vite-plugin';
import { resolve } from 'path';
import { glob } from 'glob';

export default defineConfig({
    build: {
        outDir: './public',
        emptyOutDir: false,
        manifest: 'manifest.json',
        rollupOptions: {
            output: {
                entryFileNames: 'assets/[name]-[hash].js',
                chunkFileNames: 'assets/[name]-[hash].js',
                assetFileNames: 'assets/[name]-[hash].[ext]'
            }
        },
        // Optimize chunks
        chunkSizeWarningLimit: 1000,
        target: 'es2015',
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true,
                drop_debugger: true
            }
        },
        // CSS optimization
        cssCodeSplit: true,
        cssMinify: true
    },
    
    // SSR optimization
    ssr: {
        noExternal: [
            'chart.js/**',
            'bootstrap-italia/**',
            '@popperjs/core'
        ]
    },
    
    plugins: [
        laravel({
            publicDirectory: '../../../public_html/',
            input: [
                // Main application assets
                resolve(__dirname, 'resources/css/app.css'),
                resolve(__dirname, 'resources/js/app.js'),
                
                // Bootstrap Italia components CSS
                resolve(__dirname, 'resources/css/bootstrap-italia.css'),
                
                // Component-specific assets
                resolve(__dirname, 'resources/js/components/bootstrap-italia.js'),
                
                // Additional theme assets
                resolve(__dirname, 'resources/css/theme.css'),
                resolve(__dirname, 'resources/js/theme.js'),
            ],
            refresh: [
                ...refreshPaths,
                'app/Livewire/**',
                'resources/views/**/*.blade.php',
                'resources/views/components/bootstrap-italia/**/*.blade.php',
            ],
        }),
    ],
    
    resolve: {
        alias: {
            '@': resolve(__dirname, 'resources'),
            '@components': resolve(__dirname, 'resources/views/components'),
            '@bootstrap-italia': resolve(__dirname, 'resources/views/components/bootstrap-italia'),
        },
    },
    
    // Optimization options
    optimizeDeps: {
        include: [
            'alpinejs',
            '@alpinejs/intersect',
            '@alpinejs/persist',
            'chart.js',
            'bootstrap-italia'
        ]
    },
    
    // CSS preprocessing options
    css: {
        preprocessorOptions: {
            scss: {
                additionalData: `@import "@/sass/variables";`
            }
        },
        devSourcemap: true
    },
    
    // Development server options
    server: {
        hmr: {
            overlay: false
        },
        host: '0.0.0.0',
        port: 5173
    }
});
