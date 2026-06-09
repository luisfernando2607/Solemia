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
                serif: ['Playfair Display', 'Georgia', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                olive: {
                    50: '#f7faf3',
                    100: '#eef5e4',
                    200: '#dce9c8',
                    300: '#bfd79e',
                    400: '#9abe6d',
                    500: '#6B8E4E',
                    600: '#556F3E',
                    700: '#3E4F2E',
                    800: '#2F3D23',
                    900: '#1A2B15',
                    950: '#0D1609',
                },
                terra: {
                    50: '#fdf6f0',
                    100: '#f9e8d6',
                    200: '#f2cfb0',
                    300: '#e8ae7e',
                    400: '#dd8a50',
                    500: '#cc6b32',
                    600: '#b2562a',
                    700: '#8f4224',
                    800: '#753621',
                    900: '#5f2d1d',
                },
                gold: {
                    50: '#fdf9ed',
                    100: '#f9efcb',
                    200: '#f2de92',
                    300: '#e9c85a',
                    400: '#d4a843',
                    500: '#bf8c2a',
                    600: '#a06d1f',
                    700: '#7f4f1b',
                    800: '#69401c',
                    900: '#5a351c',
                },
                cream: '#F5F0E8',
            },
        },
    },

    plugins: [forms],
};
