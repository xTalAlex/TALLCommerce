const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors');

module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
    darkMode: 'class',
    theme: {
        extend: {
            fontFamily: {
                sans: ['DM Sans' , ...defaultTheme.fontFamily.sans],
            },
            colors: { 
                primary: colors.orange,
                secondary : colors.violet,
                gray : colors.gray,
                danger: colors.rose,
                success: colors.green,
                warning: colors.amber,
            },
        },
    },

    plugins: [
        require('@tailwindcss/forms'), 
        require('@tailwindcss/typography'),
    ],
};
