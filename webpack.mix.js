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

// mix.js('resources/js/app.js', 'public/js')
//     .sass('resources/sass/app.scss', 'public/css');

const node = './node_modules';
const sbAdmin2 = node + '/startbootstrap-sb-admin-2';

// ========================================================================================================================

mix.styles(node + '/@fortawesome/fontawesome-free/css/all.css', 'public/css/fontawesome.css');
mix.styles(node + '/sweetalert2/dist/sweetalert2.css', 'public/css/sweetalert2.css');
mix.styles(node + '/datatables.net-bs4/css/dataTables.bootstrap4.css', 'public/css/dataTables.bootstrap4.css');
mix.styles(sbAdmin2 + '/css/sb-admin-2.css', 'public/css/sb-admin-2.css');
mix.sass(node + '/bootstrap-select/sass/bootstrap-select.scss', 'public/css');
mix.sass(node + '/bootstrap-colorpicker/src/sass/colorpicker.scss', 'public/css');
mix.sass(node + '/bootstrap-fileinput/scss/fileinput.scss', 'public/css');
mix.sass(node + '/tempusdominus-bootstrap-4/src/sass/tempusdominus-bootstrap-4-build.scss', 'public/css/bootstrap-datetimepicker.css');

// ========================================================================================================================

mix.copyDirectory(node + '/@fortawesome/fontawesome-free/sprites', 'public/sprites');
mix.copyDirectory(node + '/@fortawesome/fontawesome-free/svgs', 'public/svgs');
mix.copyDirectory(node + '/@fortawesome/fontawesome-free/webfonts', 'public/webfonts');

// ========================================================================================================================

mix.copy(sbAdmin2 + '/vendor/jquery/jquery.js', 'public/js');
mix.copy(node + '/bootstrap/dist/js/bootstrap.bundle.js', 'public/js');
mix.copy(node + '/moment/min/moment-with-locales.min.js', 'public/js/moment.js');
mix.copy(node + '/moment-timezone/builds/moment-timezone-with-data.min.js', 'public/js/moment-timezone.js');
mix.copy(node + '/jquery.easing/jquery.easing.min.js', 'public/js/jquery.easing.js');
mix.copy(node + '/sweetalert2/dist/sweetalert2.all.min.js', 'public/js/sweetalert2.js');

mix.copy(node + '/datatables.net/js/jquery.dataTables.min.js', 'public/js/jquery.dataTables.js');
mix.copy(node + '/datatables.net-bs4/js/dataTables.bootstrap4.min.js', 'public/js/dataTables.bootstrap4.js');

mix.copy(node + '/autosize/dist/autosize.min.js', 'public/js/autosize.js');
mix.copy(node + '/bootstrap-select/dist/js/bootstrap-select.min.js', 'public/js/bootstrap-select.js');
mix.copy(node + '/bootstrap-fileinput/js/fileinput.min.js', 'public/js/fileinput.js');
mix.copy(node + '/bootstrap-fileinput/themes/fa/theme.min.js', 'public/js/fileinput-fa.js');
mix.copy(node + '/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js', 'public/js/bootstrap-colorpicker.js');
mix.copy(node + '/infinite-scroll/dist/infinite-scroll.pkgd.min.js', 'public/js/infinite-scroll.js')
mix.copy(node + '/tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.min.js', 'public/js/bootstrap-datetimepicker.js');
mix.copy(node + '/jquery-mask-plugin/dist/jquery.mask.min.js', 'public/js/jquery.mask.js');

mix.js(sbAdmin2 + '/js/sb-admin-2.js', 'public/js');

// ========================================================================================================================

mix.sass('resources/sass/loader.scss', 'public/css');
mix.js('resources/js/bootstrap-strength-meter.js', 'public/js');
mix.js('resources/js/ajaxForm.js', 'public/js');

if (mix.inProduction()) {
    mix.version();
}
