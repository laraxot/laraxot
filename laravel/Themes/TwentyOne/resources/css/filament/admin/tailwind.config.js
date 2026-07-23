import preset from '../../../../../../../../vendor/filament/support/tailwind.config.preset'

/** @type {import('tailwindcss').Config} */
export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './../../../../Modules/**/Filament/**/*.php',
        './../../../../Modules/**/resources/views/**/*.blade.php',
        './../../../../resources/views/filament/**/*.blade.php',
        './../../../../vendor/filament/**/*.blade.php',
        './../../../../resources/views/**/*.blade.php',
        './../../../../storage/framework/views/*.php',
        './../../../../vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    ],
    theme: {
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
            fontFamily: {
                'display': ['Inter', 'system-ui', 'sans-serif'],
                'body': ['Inter', 'system-ui', 'sans-serif'],
            },
            animation: {
                'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                'bounce-in': 'bounceIn 0.5s ease-out',
            }
        }
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ]
}