const mix = require('laravel-mix');
const path = require('path');

require('laravel-mix-purgecss');

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
    .vue()
    .sass('resources/sass/dark-app.scss', 'public/css')
    .sass('resources/sass/light-app.scss', 'public/css')
    .purgeCss({
        enabled: mix.inProduction(),
        content: [
            "app/**/*.php",
            "resources/**/*.html",
            "resources/**/*.php",
        ],
    });

// For debugging on prod enable this
// mix.sourceMaps();

if (mix.inProduction()) {
    mix.version();
}
