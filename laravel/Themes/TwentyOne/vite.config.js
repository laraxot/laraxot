import { defineConfig } from "vite";
import laravel, { refreshPaths } from "laravel-vite-plugin";
import tailwindcss from '@tailwindcss/vite'
import path from 'path'
import { fileURLToPath } from 'url'

const __dirname = path.dirname(fileURLToPath(import.meta.url))

export default defineConfig({
	build: {
		outDir: "./public",
		emptyOutDir: false,
        manifest: 'manifest.json',
		rollupOptions: {
			output: {
				entryFileNames: 'assets/[name]-[hash].js',
				chunkFileNames: 'assets/[name]-[hash].js',
				assetFileNames: 'assets/[name]-[hash].[ext]',
			},
		},
	},
	ssr: {
		noExternal: ["chart.js/**"],
	},
	plugins: [
		laravel({
			publicDirectory: "../../../public_html/",
			input: [
				path.resolve(__dirname, "./resources/css/app.css"),
				path.resolve(__dirname, "./resources/js/app.js"),
				//path.resolve(__dirname, "./resources/css/filament/admin/theme.css")
			],
			refresh: [...refreshPaths, "app/Livewire/**"],
		}),
		tailwindcss(),
	],
});
