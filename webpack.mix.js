let mix = require('laravel-mix');

mix.options({
    extractVueStyles: true
});

mix.js(['resources/assets/js/app.js', 'resources/assets/js/helpers/Errors.js'], 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css')
   .sass('resources/assets/sass/login.scss', 'public/css/login.css');
