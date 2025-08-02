import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'azul-claro': '#a7b9e9',
                'rosado-pastel': '#f3b3c5',
                'azul-intenso': '#0048f4',
                'verde-lima': '#d7e17c',
                brand: {
                    'light-blue': '#a7b9e9',
                    'pastel-pink': '#f3b3c5',
                    'intense-blue': '#0048f4',
                    'soft-lime': '#d7e17c',
                }
            },
        },
    },

    plugins: [forms],
};
