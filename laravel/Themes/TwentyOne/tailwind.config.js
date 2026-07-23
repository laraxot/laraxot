/** @type {import('tailwindcss').Config} */
export default {
	content: [
		'./resources/**/*.{html,js,blade.php}',
		'./resources/views/**/*.blade.php',
		'../../Modules/**/Filament/**/*.php',
		'../../Modules/**/resources/views/**/*.blade.php',
		'../../resources/views/filament/**/*.blade.php',
		'../../vendor/filament/**/*.blade.php',
		'../../resources/views/**/*.blade.php',
		'../../storage/framework/views/*.php',
		'../../vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
		'./node_modules/flowbite/**/*.js',
	],
	theme: {
		fontFamily: {
			sans: ["Figtree", "ui-sans-serif", "system-ui", "sans-serif", "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"],
		},
		extend: {
			colors: {
				// Prediction market color scheme
				'market': {
					'yes': '#10b981',      // green-500
					'no': '#ef4444',       // red-500
					'neutral': '#6b7280',  // gray-500
				},
				'probability': {
					'high': '#059669',     // emerald-600
					'medium': '#d97706',   // amber-600
					'low': '#dc2626',      // red-600
				}
			},
			animation: {
				'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
				'bounce-in': 'bounceIn 0.5s ease-out',
			}
		},
	},
}