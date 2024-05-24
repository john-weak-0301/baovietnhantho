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
const externals = {
  'vue': 'Vue',
  'react': 'React',
  'react-dom': 'ReactDOM',
  'jquery': 'jQuery',
  'lodash': 'lodash',
  '@wordpress/api-fetch': {this: ['wp', 'apiFetch']},
  '@wordpress/blocks': {this: ['wp', 'blocks']},
  '@wordpress/components': {this: ['wp', 'components']},
  '@wordpress/block-editor': {this: ['wp', 'blockEditor']},
  '@wordpress/compose': {this: ['wp', 'compose']},
  '@wordpress/data': {this: ['wp', 'data']},
  '@wordpress/dom': {this: ['wp', 'dom']},
  '@wordpress/editor': {this: ['wp', 'editor']},
  '@wordpress/element': {this: ['wp', 'element']},
  '@wordpress/hooks': {this: ['wp', 'hooks']},
  '@wordpress/i18n': {this: ['wp', 'i18n']},
  '@wordpress/url': {this: ['wp', 'url']},
};

mix.disableSuccessNotifications().
  js('resources/js/main.js', 'public/js').
  js('resources/js/branch.js', 'public/js').
  js('resources/js/tool.js', 'public/js').
  js('resources/js/counselors.js', 'public/js').
  js('resources/js/compare.js', 'public/js').
  js('resources/js/fund.js', 'public/js').
  react('resources/js/blocks.js', 'public/js').
  sass('resources/sass/main.scss', 'public/css');

if (mix.inProduction()) {
  mix.version();

  mix.styles([
    'resources/libs/jquery-ui/jquery-ui.min.css',
    'resources/libs/swiper4.7/swiper.min.css',
    'resources/libs/select2/select2.min.css',
    'resources/libs/magnific-popup/magnific-popup.min.css',
  ], 'public/css/vendor.css');

  mix.scripts([
    'resources/libs/_jquery/jquery.min.js',
    'resources/libs/jquery-ui/jquery-ui.min.js',
    'resources/libs/swiper4.7/swiper.jquery.min.js',
    'resources/libs/slickjs/slick.js',
    'resources/libs/select2/select2.js',
    'resources/libs/headroom/headroom.js',
    'resources/libs/imagesloaded/imagesloaded.pkgd.js',
    'resources/libs/jquery.matchHeight/jquery.matchHeight.min.js',
    'resources/libs/jquery.waypoints/jquery.waypoints.min.js',
    'resources/libs/jquery.countTo/jquery.countTo.min.js',
    'resources/libs/jquery-one-page/jquery.nav.js',
    'resources/libs/mdGridJs/mdGridJs.js',
    'resources/libs/accordion/awe-accordion.min.js',
    'resources/libs/tabs/awe-tabs.min.js',
    'resources/libs/magnific-popup/jquery.magnific-popup.min.js',
    'node_modules/lodash/lodash.min.js',
    'node_modules/vue/dist/vue.min.js',
  ], 'public/js/vendor.js');
}

mix.browserSync({
  proxy: process.env.MIX_BROWSER_SYNC_URL || 'baovietnhantho.local',
});

mix.webpackConfig({
  externals,
  output: {
    libraryTarget: 'this',
  },
});

mix.options({
  processCssUrls: false,
});
