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
        extend: {
            fontFamily: {
                sans: ['DinPro', 'sans'],
                serif: ['RoxboroughCF', 'serif'],
            },
            colors: {
                primary: {// froly
                    '50': '#FDE4E4',
                    '100': '#FCD8D9',
                    '200': '#FBC0C2',
                    '300': '#F9A8AA',
                    '400': '#F89093',
                    '500': '#F6787C',
                    '600': '#F24349',
                    '700': '#EE1017',
                    '800': '#B90C12',
                    '900': '#85090D'
                },
                secondary: {// botticelli
                    "50": "#DCE4EE",
                    "100": "#C1CFE0",
                    "200": "#A8BAD1",
                    "300": "#8FA5C1",
                    "400": "#7790B0",
                    "500": "#607C9E",
                    "600": "#526883",
                    "700": "#445468",
                    "800": "#36404E",
                    "900": "#262D35"
                },
                accent: {// scarlet
                    "50": "#FFCABE",
                    "100": "#FFA995",
                    "200": "#FF876C",
                    "300": "#FF6644",
                    "400": "#FF441B",
                    "500": "#F12C00",
                    "600": "#C62602",
                    "700": "#9C1F03",
                    "800": "#731804",
                    "900": "#4B1003"
                },
                gray: { // black-haze
                    "50": "#E9EBEB",
                    "100": "#D4D7D7",
                    "200": "#C1C2C2",
                    "300": "#ADADAD",
                    "400": "#989898",
                    "500": "#848484",
                    "600": "#707070",
                    "700": "#5B5B5B",
                    "800": "#474747",
                    "900": "#323232"
                },
                neutral: {// bleach-white
                    "50": "#FEEDD7",
                    "100": "#FCDBB0",
                    "200": "#FAC98A",
                    "300": "#F6B764",
                    "400": "#F2A440",
                    "500": "#ED921C",
                    "600": "#CD7C13",
                    "700": "#A66511",
                    "800": "#804F0F",
                    "900": "#5A380C"
                },
                danger: { // mexican-red
                    "50": "#F7CDCD",
                    "100": "#F0ABAB",
                    "200": "#E88A8A",
                    "300": "#E06A6A",
                    "400": "#D74A4A",
                    "500": "#C83030",
                    "600": "#A52A2A",
                    "700": "#832323",
                    "800": "#611C1C",
                    "900": "#411414"
                },
                success: { // fern-green
                    "50": "#E8F4E4",
                    "100": "#CFE8C8",
                    "200": "#B7DBAC",
                    "300": "#9FCD91",
                    "400": "#88BE77",
                    "500": "#71AF5E",
                    "600": "#5F964E",
                    "700": "#4F7942",
                    "800": "#3F5D35",
                    "900": "#2E4227"
                },
                warning: { //saffron
                    "50": "#FEF1C9",
                    "100": "#FDE7A1",
                    "200": "#FBDB7B",
                    "300": "#F8D055",
                    "400": "#F4C430",
                    "500": "#ECB60F",
                    "600": "#C4970F",
                    "700": "#9C790E",
                    "800": "#755B0C",
                    "900": "#4F3E09"
                },
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
    ],
};
