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
            },
            colors: {
                // 'white': 'var(--game-white)',
                // 'black': 'var(--game-black)',
                // 'primary': 'var(--game-primary)',
                // 'secondary': 'var(--game-secondary)',
                // 'accent': 'var(--game-accent)',
            }
        },
    },

    plugins: [forms],
};
