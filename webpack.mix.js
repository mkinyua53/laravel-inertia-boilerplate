const mix = require('laravel-mix');
let webpack = require('webpack');
const BundleAnalyzerPlugin = require("webpack-bundle-analyzer").BundleAnalyzerPlugin;
const VuetifyLoaderPlugin = require('vuetify-loader/lib/plugin')
require('laravel-mix-workbox');


/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('tailwindcss'),
        require('autoprefixer')
    ])
    .extract()
    .version()
    .browserSync('localhost:8000')
    .webpackConfig(require('./webpack.config'));

if (mix.inProduction()) {
    mix.injectManifest({
        maximumFileSizeToCacheInBytes: 2194304,
        swSrc: './resources/js/sw.js'
    });
}


mix.webpackConfig({
    plugins: [
        new BundleAnalyzerPlugin(),
        new VuetifyLoaderPlugin(),
        new webpack.IgnorePlugin(/^\.\/locale$/, /moment$/),
    ]
})
