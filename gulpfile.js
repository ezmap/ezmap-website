var elixir = require('laravel-elixir');
require('laravel-elixir-vueify');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function (mix) {
    mix.sass('app.scss')
        .scripts([
            'jquery.min.js',
            // 'jquery-unveil.js',
            'bootstrap.min.js',
            'vue.min.js',
            'keen-ui.min.js',
            'pjax.js',
            'main.js'
        ]);
    mix.version(['css/app.css', 'js/all.js']);
});
