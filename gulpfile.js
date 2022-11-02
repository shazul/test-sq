var elixir = require('laravel-elixir');

// require('./aglio.elixir');

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

elixir(function(mix) {
    mix.sass('app.scss')
        .sass('bootstrap.scss')
        .less('admin-lte/AdminLTE.less')
        .styles([
            'datatables/dataTables.bootstrap.css',
            'select2/select2.min.css'
        ], 'public/css/plugins.css', 'public/plugins')
        .styles([
            'dropzone/dist/dropzone.css'
        ], 'public/css/vendors.css', 'node_modules')
        .scripts([
            'jQuery/jQuery-2.1.4.min.js',
            '../js/bootstrap.min.js',
            'datatables/jquery.dataTables.js',
            'datatables/dataTables.bootstrap.min.js',
            'select2/select2.full.min.js',
            '../js/app.min.js',
            '../../node_modules/dropzone/dist/dropzone.js',
            'iCheck/icheck.js',
        ], 'public/js/all.js', 'public/plugins')
        .copy('node_modules/vue-select2/src/vue-select.js', 'public/js/vue-select.js')
        //.aglio('api.apib')
        .browserSync({ proxy: 'pim.soprema.local', open: false })
    ;

    if(elixir.config.production) {
        mix.copy('node_modules/vue/dist/vue.min.js', 'public/js/vue.js');
    } else {
        mix.copy('node_modules/vue/dist/vue.js', 'public/js/vue.js');
    }

});
