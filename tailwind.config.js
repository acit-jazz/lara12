const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    // mode: 'jit',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Halcom', ...defaultTheme.fontFamily.sans],
            },
            colors: {
              'primary': '#E0A75E',
              'secondary': '#49C4F0',
              'dark': '#020300',
              'darkblue' : '#79A9D1',
              'darkgray': '#59544B',
              'lightgray': '#7D8CA3',
              'grey':'rgba(6,57,64,0.6',
              'army':'#779945',
              'lightyellow':'#FEF9D1',
              'tr' : 'rgba(5, 57, 109, 0.2)'
            },
            boxShadow: {
              'dark': '0 0 15px rgba(0, 0, 0, 0.9)',
            },
            keyframes: {
              radar: {
                '0%, 100%': {opacity:0.8, transform: 'scale(0.9)' },
                '50%': { opacity:1,transform: 'scale(1.1)' },
              }
            },
            animation: {
              radar: 'radar 1s ease-in-out infinite',
            }
        },
    },

    plugins: [
        require('@tailwindcss/forms')
    ],
};
