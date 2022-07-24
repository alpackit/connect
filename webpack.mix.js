// webpack.mix.js
const mix = require('laravel-mix');

mix.disableSuccessNotifications();

mix.sass('assets/src/scss/main.scss', 'assets/dist/css/main.css')
    .options({
        processCssUrls: false
    });

mix.copy('assets/src/js/main.js', 'assets/dist/js/main.js').minify('assets/dist/js/main.js');
