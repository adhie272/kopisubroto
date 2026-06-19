import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                display: ['Bungee', 'Audiowide', 'Black Ops One', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    cream: '#FBF8F2',
                    parchment: '#EDE6D6',
                    deep: '#0F3531',
                    ink: '#2F5C57',
                    teal: '#5FA8A4',
                    tealSoft: '#DCEFEB',
                    brown: '#8B5E34',
                    brownDark: '#6F4624',
                    maroon: '#9A4B46',
                    muted: '#6B7280',
                    line: '#E4DDD0',
                },
            },
        },
    },

    plugins: [forms],
};
