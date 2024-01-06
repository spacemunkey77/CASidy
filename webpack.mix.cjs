const mix = require('laravel-mix');

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

mix.js('resources/js/app.js', 'public/js')
   .js('resources/js/condo.js', 'public/js')
   .js('resources/js/door.js', 'public/js')
   .js('resources/js/doorbell.js', 'public/js')
   .js('resources/js/power.js', 'public/js')
   .js('resources/js/night.js', 'public/js')
   .js('resources/js/cloudy.js', 'public/js')
   .js('resources/js/status.js', 'public/js')
   .js('resources/js/setup.js', 'public/js')
   .js('resources/js/timer.js', 'public/js')
   .js('resources/js/sensorsetup.js', 'public/js')
   .js('resources/js/buttons.js', 'public/js')
   .js('resources/js/boundary.js', 'public/js')
   .js('resources/js/sensorreport.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   .sass('resources/sass/opty.scss', 'public/css')
   .sass('resources/sass/condo.scss', 'public/css')
   .sass('resources/sass/status.scss', 'public/css')
   .sass('resources/sass/door.scss', 'public/css')
   .sass('resources/sass/doorbell.scss', 'public/css')
   .sass('resources/sass/night.scss', 'public/css')
   .sass('resources/sass/cloudy.scss', 'public/css')
   .sass('resources/sass/setup.scss', 'public/css')
   .sass('resources/sass/timer.scss', 'public/css')
   .sass('resources/sass/sensorsetup.scss', 'public/css')
   .sass('resources/sass/power.scss', 'public/css')
   .sass('resources/sass/boundary.scss', 'public/css')
   .sass('resources/sass/authenticate.scss', 'public/css')
   .sass('resources/sass/sensorreport.scss', 'public/css')
   .sass('resources/sass/buttons.scss', 'public/css');
