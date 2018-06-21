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
  .js('resources/assets/js/managers-list.js', 'public/js')
  .js('resources/assets/js/restaurant-form.js', 'public/js')
  .js('resources/assets/js/restaurants-list.js', 'public/js')
  .js([
    'node_modules/datatables.net/js/jquery.dataTables.min.js',
    'node_modules/datatables.net-bs/js/dataTables.bootstrap.min.js',
    'node_modules/datatables.net-responsive/js/dataTables.responsive.min.js',
    'node_modules/datatables.net-responsive-bs/js/responsive.bootstrap.min.js'
  ], 'public/js/dataTables.bootstrap.min.js')
  .styles([
    'node_modules/datatables.net-bs/css/dataTables.bootstrap.min.css',
    'node_modules/datatables.net-responsive-bs/css/responsive.bootstrap.min.css'
  ], 'public/css/dataTables.bootstrap.min.css')
  .copy('resources/assets/images', 'public/images')
  .copy([
    'resources/assets/css/main.css'
  ], 'public/css')
