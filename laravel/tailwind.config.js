const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
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
                'teal-dark': '#1a5e63',
                'teal-medium': '#238074',
                'teal-light': '#1a9e8c',
                'teal-lighter': '#43b6a7',
                'gold': '#ffbd38',
                'orange': '#fa4616',
                'rust': '#bb3e03',
            }
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
