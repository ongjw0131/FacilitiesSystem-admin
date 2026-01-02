/** @type {import('tailwindcss').Config} */
module.exports = {
    darkMode: 'class', // enable dark mode via class (controlled manually, not by browser)
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            colors: {
                primary: '#135bec',
                'background-light': '#f6f6f8',
                'background-dark': '#101622',
            },
            fontFamily: {
                display: ['Lexend', 'sans-serif'],
            },
            borderRadius: {
                DEFAULT: '0.25rem',
                lg: '0.5rem',
                xl: '0.75rem',
                full: '9999px',
            },
            keyframes: {
                'fade-in': {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' }
                },
                'fade-in-up': {
                    '0%': { opacity: '0', transform: 'translateY(10px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' }
                }
            },
            animation: {
                'fade-in': 'fade-in 1s ease-out forwards',
                'fade-in-up': 'fade-in-up 1s ease-out forwards'
            }
        },
    },
    plugins: [require('@tailwindcss/forms'), require('@tailwindcss/container-queries')],
};
