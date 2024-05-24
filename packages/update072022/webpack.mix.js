let mix = require('laravel-mix');

const externals = {
  'axios': 'axios',
  'jquery': 'jQuery',
  'stimulus': 'window', // Exported via orchid.js
};

mix
  .setPublicPath('dist')
  .js('src/index.js', 'dist/').react();

mix.disableSuccessNotifications();

if (mix.inProduction()) {
  mix.copyDirectory('dist/', '../../public/vendor/links-picker');
}

mix.webpackConfig({
  externals,
});

mix.options({
  processCssUrls: false,
});
