var mix = require('laravel-mix')

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/assets/js/app.js', 'public/js')
  .js('resources/assets/js/manager-form.js', 'public/js')
  .copy('resources/assets/images', 'public/images')
  .copy([
    'resources/assets/css/main.css'
  ], 'public/css')
  .copy([
    'resources/assets/js/managers-list.js'
  ], 'public/js')
