let mix = require('laravel-mix');

/**
 * The webpack externals library.
 *
 * @type {object}
 */
const externals = {
  'react': 'React',
  'react-dom': 'ReactDOM',
  'jquery': 'jQuery',
  'moment': 'moment',
  'axios': 'axios',
  'lodash': 'lodash',
};

mix.setPublicPath('dist').
  react('src/index.js', 'dist/gutenberg.js').
  sass('scss/gutenberg.scss', 'dist').
  sourceMaps();

mix.copy('dist', '../../public/vendor/gutenberg');

mix.disableNotifications();

mix.webpackConfig({
  externals,
  output: {
    libraryTarget: 'this',
  },
});

mix.options({
  processCssUrls: false,
});
