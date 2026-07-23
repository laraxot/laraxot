import defaultTheme from 'tailwindcss/defaultTheme';

export default {
    darkMode: 'class',
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.css',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Titillium Web', 'Inter var', ...defaultTheme.fontFamily.sans],
                mono: ['Roboto Mono', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                primary: {
                    50: '#e6f7f0',
                    500: '#00814A',
                    600: '#006b3d',
                    DEFAULT: '#00814A',
                },
                success: {
                    500: '#00B373',
                    DEFAULT: '#00B373',
                },
                danger: {
                    500: '#D9364F',
                    DEFAULT: '#D9364F',
                },
                warning: {
                    500: '#F5A623',
                    DEFAULT: '#F5A623',
                },
            },
        },
    },
    plugins: [],
};
