let mix = require('laravel-mix')
const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;


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
mix.webpackConfig({
  output: {
    chunkFilename: 'js/chunks/[name].js',
    publicPath: '/',
  },
  plugins: [
    new BundleAnalyzerPlugin()
  ]
})

mix.react('resources/assets/js/app.js', 'public/js')
mix.version()
