const { mix } = require('laravel-mix');

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
mix.options({ processCssUrls: false });
mix.js('resources/assets/js/app.js', 'public/js')
	.less('resources/assets/less/app.less', 'public/css/alte-app.css')
	.sass('resources/assets/sass/app.scss', 'public/css')

	.js('node_modules/semantic-ui/dist/semantic.js', 'public/js/semantic.js')
	.css('node_modules/semantic-ui/dist/semantic.css', 'public/css/semantic.css')
	.js([
            'resources/assets/js/app.js',
            'resources/assets/admin-lte/js/app.js',
            'resources/assets/admin-lte/plugins/slimScroll/jquery.slimscroll.min.js',
        ],'public/js/lte-app.js');

