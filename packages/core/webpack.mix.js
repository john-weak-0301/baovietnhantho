let mix = require('laravel-mix');

/**
 * The externals library.
 *
 * @type {object}
 */
const externals = {
  'axios': 'axios',
  'jquery': 'jQuery',
  'stimulus': 'window', // Exported via orchid.js
};

mix
  .setPublicPath('dist')
  .disableSuccessNotifications()
  .js('resources/js/dashboard.js', 'dist/js')
  .js('resources/js/wp-media.js', 'dist/js')
  // .sass('resources/sass/dashboard.scss', 'dist/css')
  .css('resources/sass/_media-views.css', 'dist/css/media-views.css');

if (mix.inProduction()) {
  mix
    .version()
    .copyDirectory('resources/img/', 'dist/img')
    .copyDirectory('resources/fonts/', 'dist/fonts')
    .copyDirectory('dist/', '../../public/vendor/dashboard');
}

mix.browserSync({
  proxy: process.env.MIX_BROWSER_SYNC_URL || 'baovietnhantho.local',
  files: [
    'dist/js/*.js',
    'dist/css/*.css',
  ]
});

mix.webpackConfig({
  externals,
  watchOptions: {
    ignored: /node_modules/,
  },
  resolve: {
    extensions: ['.scss'],
    alias: {
      '@orchid': global.path.resolve(__dirname, '../../vendor/orchid/platform/resources')
    }
  },
  output: {
    libraryTarget: 'this',
  }
});

mix.options({
  processCssUrls: false,
});
