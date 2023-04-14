const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors');

module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './app/filament/**/*.blade.php',
    ],
    darkMode: 'class',
    theme: {
        fontFamily: {
            'sans': ['Alegreya Sans SC',  ...defaultTheme.fontFamily.sans],
            'serif': ['Charmonman',  ...defaultTheme.fontFamily.serif],
        },
        extend: {
            colors: {
                primary: {
                    "50": "#DBF3F5",
                    "100": "#BDE8EB",
                    "200": "#9FDDE0",
                    "300": "#81D0D4",
                    "400": "#65C3C8",
                    "500": "#49B5BB",
                    "600": "#3E989D",
                    "700": "#347B7E",
                    "800": "#2A5D60",
                    "900": "#1E4143"
                },
                danger: colors.rose,
                success: colors.green,
                warning: colors.yellow,
            },
            animation: {
                shine: "shine 1s",
            },
            keyframes: {
                shine: {
                    "100%": { left: "125%" },
                },
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
        require('daisyui'),
    ],
    daisyui: {
        themes: ['cupcake'],
        logs: false,
    },
};
