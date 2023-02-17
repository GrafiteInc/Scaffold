const mix = require('laravel-mix');
const path = require('path');

require('laravel-mix-purgecss');
require('laravel-mix-compress');

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
    .sass('resources/sass/dark-app.scss', 'public/css')
    .sass('resources/sass/light-app.scss', 'public/css')
    .purgeCss({
        enabled: mix.inProduction(),
        content: [
            "app/**/*.php",
            "resources/**/*.vue",
            "resources/**/*.html",
            "resources/**/*.php",
        ],
    });

// For debugging on prod enable this
// mix.sourceMaps();

// mix.webpackConfig({
//     stats: {
//          children: true
//     }
// });

if (mix.inProduction()) {
    mix.compress();
    mix.version();
}
