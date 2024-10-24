const mix = require('laravel-mix');
const path = require('path');
const webpack = require('webpack');

mix.js('resources/js/app.js', 'public/js')
   .vue({
     version: 3
   })
   .postCss('resources/css/app.css', 'public/css', [
      require('postcss-import'),
      require('tailwindcss'),
      require('autoprefixer'),
   ])
   .sass('resources/sass/app.scss', 'public/css')
   .version();

mix.webpackConfig({
    stats: {
        children: true,
    },
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'),
            vue$: 'vue/dist/vue.esm-bundler.js'
        },
        extensions: ['.*', '.wasm', '.mjs', '.js', '.jsx', '.json', '.vue', '.ts', '.tsx'],
    },
    module: {
        rules: [
            {
                test: /\.vue$/,
                loader: 'vue-loader',
            },
        ],
    },
    plugins: [
        new webpack.DefinePlugin({
            '__VUE_PROD_HYDRATION_MISMATCH_DETAILS__': JSON.stringify(true),
        }),
    ],
});
