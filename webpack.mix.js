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

mix.styles('resources/assets/backend/css/film.css', 'public/build/backend/css/film.css');
mix.styles('resources/assets/backend/css/movie.css', 'public/build/backend/css/movie.css');
mix.js('resources/assets/backend/js/movie.js', 'public/build/backend/js/movie.js');
mix.js('resources/assets/backend/js/crudmovie.js', 'public/build/backend/js/crudmovie.js');
mix.js('resources/assets/backend/js/video.js', 'public/build/backend/js/video.js');
mix.js('resources/assets/backend/js/crudvideo.js', 'public/build/backend/js/crudvideo.js');
