let mix = require('laravel-mix');

mix.sass('resources/assets/sass/app.scss', 'public/css/')
  .js([
    'resources/assets/js/bootstrap.min.js',
    'resources/assets/js/sweetalert.js',
    'resources/assets/js/pjax.js',
    'resources/assets/js/main.js'
  ], 'public/js/all.js');

mix.version(['public/css/app.css', 'public/js/all.js']);
