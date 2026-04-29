import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/views/livewire/*.blade.php',
        "./resources/**/*.js",
        "./resources/**/*.vue",
        './vendor/rappasoft/laravel-livewire-tables/resources/views/**/*.blade.php',
    ],
    safelist: [
        'grid-cols-1',
        'grid-cols-2',
        'grid-cols-3',
        'grid-cols-4',
    ],
    theme: {
        extend: {
            colors:{
                primary: "#1E3A8A",
                secondary: "#DBEAFE"
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    darkMode: 'class',
    plugins: [
        forms,
        'tailwindcss-rtl'
    ],
};
