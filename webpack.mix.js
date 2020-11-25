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
mix.styles('resources/assets/backend/css/playlist.css', 'public/build/backend/css/playlist.css');
mix.styles('resources/assets/frontend/css/rate.css', 'public/build/frontend/css/rate.css');
mix.styles('resources/assets/backend/css/chart.css', 'public/build/backend/css/chart.css');
mix.js('resources/assets/backend/js/channel.js', 'public/build/backend/js/channel.js');
mix.js('resources/assets/backend/js/crudchannel.js', 'public/build/backend/js/crudchannel.js');
mix.js('resources/assets/backend/js/movie.js', 'public/build/backend/js/movie.js');
mix.js('resources/assets/backend/js/crudmovie.js', 'public/build/backend/js/crudmovie.js');
mix.js('resources/assets/backend/js/playlist.js', 'public/build/backend/js/playlist.js');
mix.js('resources/assets/backend/js/crudplaylist.js', 'public/build/backend/js/crudplaylist.js');
mix.js('resources/assets/backend/js/video.js', 'public/build/backend/js/video.js');
mix.js('resources/assets/backend/js/crudvideo.js', 'public/build/backend/js/crudvideo.js');
mix.js('resources/assets/backend/js/source.js', 'public/build/backend/js/source.js');
mix.js('resources/assets/backend/js/type.js', 'public/build/backend/js/type.js');
mix.js('resources/assets/backend/js/user.js', 'public/build/backend/js/user.js');
mix.js('resources/assets/backend/js/cruduser.js', 'public/build/backend/js/cruduser.js');
mix.js('resources/assets/backend/js/request.js', 'public/build/backend/js/request.js');
mix.js('resources/assets/frontend/js/action.js', 'public/build/frontend/js/action.js');
mix.js('resources/assets/backend/js/crudtype.js', 'public/build/backend/js/crudtype.js')
mix.js('resources/assets/backend/js/chart.js', 'public/build/backend/js/chart.js')
