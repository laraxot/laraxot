import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms'
import typography from '@tailwindcss/typography'
import colors from 'tailwindcss/colors';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    safelist: [
        // Filament Form component classes (runtime generated)
        'fi-fo-section',
        'fi-fo-section-header',
        'fi-fo-section-header-heading',
        'fi-fo-section-header-description',
        'fi-fo-field-wrp',
        'fi-fo-field-wrp-label',
        'fi-fo-field-wrp-helper-text',
        'fi-fo-field-wrp-description',
        'fi-fo-required-indicator',
        'fi-fo-select',
        'fi-fo-file-upload',
        'fi-fo-checkbox',
        'fi-fo-checkbox-label',
        'fi-fo-wizard-actions',
        // Filament Infolist component classes (runtime generated)
        'fi-in-text-item',
        'fi-in-text-item-icon',
        // Filament Wizard classes
        'fi-wiz-steps',
        'fi-wiz-step-actions',
        'fi-wiz-step-indicator',
        'fi-wiz-step-indicator-state-completed',
        'fi-wiz-step-indicator-state-active',
        'fi-wiz-step-indicator-state-inactive',
        // Custom parity classes
        'wizard-required-note',
        'segnalazione-dettaglio-page',
        'segnalazione-area-personale-page',
        'auth-login-card',
        'auth-register-card',
        // Select form-select
        'form-select',
        // Bootstrap Italia grid classes (used in ticket-list layout)
        'col-lg-3', 'col-lg-4', 'col-lg-5', 'col-lg-6', 'col-lg-8', 'col-lg-9', 'col-lg-10',
        'col-md-3', 'col-md-4', 'col-md-6', 'col-md-9',
        'col-sm-6', 'col-xl-3', 'col-xl-4', 'col-xl-6', 'col-xl-8',
        'offset-lg-1', 'offset-xl-2',
        'min-vh-lg-50',
    ],
    content: [
        // Theme resources
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.css',
        './resources/**/*.vue',
        './assets/**/*.js',
        './assets/**/*.css',

        // Filament core packages
        './vendor/filament/**/*.blade.php',

        // Laravel application paths
        '../../app/Filament/**/*.php',
        '../../resources/views/**/*.blade.php',
        '../../Modules/**/Filament/**/*.php',
        '../../Modules/**/resources/views/**/*.blade.php',
        '../../storage/framework/views/*.php',

        // Third-party packages
        './node_modules/flowbite/**/*.js',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Titillium Web', 'Inter var', ...defaultTheme.fontFamily.sans],
                mono: ['Roboto Mono', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                // Design Comuni PA Official Colors
                primary: {
                    50: '#e6f7f0',
                    100: '#b3e6d1',
                    200: '#80d5b2',
                    300: '#4dc493',
                    400: '#1ab374',
                    500: '#007A52', // Verde PA
                    600: '#006945',
                    700: '#005838',
                    800: '#00472b',
                    900: '#00361e',
                    DEFAULT: '#007A52',
                },
                'italia-blue': {
                    50: '#e6f2ff',
                    100: '#b3d9ff',
                    200: '#80bfff',
                    300: '#4da6ff',
                    400: '#1a8cff',
                    500: '#0066CC', // Blue Design Comuni
                    600: '#0052a3',
                    700: '#003d7a',
                    800: '#002952',
                    900: '#001429',
                    DEFAULT: '#0066CC',
                },
                'italia-blue-dark': '#003D73',
                'italia-gray': {
                    50: '#F2F2F2',
                    100: '#E0E0E0',
                    200: '#CCCCCC',
                    300: '#999999',
                    400: '#666666',
                    500: '#333333',
                    DEFAULT: '#333333',
                },
            },
            spacing: {
                '18': '4.5rem',
                '88': '22rem',
            },
            borderRadius: {
                'none': '0px',
                'sm': '2px',
                DEFAULT: '4px',
                'md': '6px',
                'lg': '8px',
            },
        },
    },
    plugins: [
        forms,
        typography,
        require("flowbite/plugin"),
        function({ addComponents, theme }) {
            addComponents({
                // Header & Layout components using normalized tokens
                '.it-header-slim-wrapper': {
                    '@apply bg-italia-blue-dark border-b border-white/20': {},
                },
                '.it-header-center-wrapper': {
                    '@apply bg-white py-6': {},
                },
                '.it-brand-title': {
                    '@apply text-italia-blue text-2xl font-bold leading-tight m-0': {},
                },
                '.it-brand-tagline': {
                    '@apply text-italia-gray-400 text-sm mt-1': {},
                },
                '.btn-primary': {
                    '@apply bg-italia-blue text-white hover:bg-italia-blue-dark transition-colors': {},
                },
                '.btn-outline-primary': {
                    '@apply bg-transparent text-italia-blue border border-italia-blue hover:bg-italia-blue hover:text-white transition-colors': {},
                },
                '.text-primary': {
                    '@apply text-italia-blue': {},
                },
                '.card-teaser': {
                    '@apply bg-white rounded-lg border border-italia-gray-100 shadow-sm hover:shadow-md transition-shadow': {},
                },
            });
        },
    ],
}
