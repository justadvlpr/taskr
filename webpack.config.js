const path = require('path');
const VuetifyLoaderPlugin = require('vuetify-loader/lib/plugin');

module.exports = {
    resolve: {
        extensions: [".vue", ".ts", ".js", ".scss", ".css"],
        alias: {
            '@': path.resolve(__dirname, './resources/assets/js/')
        }
    },
    plugins: [
        new VuetifyLoaderPlugin()
    ],
    output: {
        publicPath: '/assets/',
        chunkFilename: '[name].[chunkhash].js',
        //filename: '[name].[chunkhash].js',
    }
};
