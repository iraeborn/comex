const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css');

mix.styles([
    'node_modules/@fortawesome/fontawesome-free/css/all.css',
    'node_modules/simple-line-icons/css/simple-line-icons.css',
    'node_modules/@coreui/coreui/dist/css/coreui.min.css',
    'node_modules/select2/dist/css/select2.min.css'
    ], 'public/css/style.css');

mix.scripts([
    'node_modules/jquery/dist/jquery.min.js',
    'node_modules/popper.js/dist/umd/popper.min.js',
    'node_modules/bootstrap/dist/js/bootstrap.min.js',
    'node_modules/@coreui/coreui/dist/js/coreui.min.js',
    'node_modules/select2/dist/js/select2.full.min.js'
    ], 'public/js/main.js');

mix.copyDirectory('node_modules/@fortawesome/fontawesome-free/', 'public/fonts');
mix.copyDirectory('node_modules/simple-line-icons/fonts', 'public/fonts');