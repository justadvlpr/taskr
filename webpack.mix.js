const mix = require('laravel-mix');

mix
    .babelConfig({
        plugins: ['@babel/plugin-syntax-dynamic-import'],
    })
    .webpackConfig(require('./webpack.config'))
    .setResourceRoot('/assets/')
    .setPublicPath('./public/assets/')
    .ts('./resources/assets/js/app.ts', './public/assets/js/')
    .sass('./resources/assets/sass/app.scss', './public/assets/css/');

if (mix.inProduction()) {
    mix.version();
} else {
    mix.webpackConfig({devtool: 'source-map'}).sourceMaps();
}
