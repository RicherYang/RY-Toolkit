const path = require('path');
const glob = require('glob');
const CopyWebpackPlugin = require('copy-webpack-plugin', true);

const defaultConfig = require('@wordpress/scripts/config/webpack.config', true);
const { fromProjectRoot } = require('@wordpress/scripts/utils/file', true);

const srcPath = fromProjectRoot('assets-src');
const distPath = fromProjectRoot('assets');

function getCopyPatterns() {
    let patterns = [];

    glob.sync(
        path.join(srcPath, 'icons', '*')
    ).forEach((file) => {
        patterns.push({
            from: file,
            to: path.relative(srcPath, file)
        });
    });

    return patterns;
}

module.exports = {
    ...defaultConfig,
    entry: {
        'admin/options': path.join(srcPath, 'admin/options.js'),
        'admin/tools': path.join(srcPath, 'admin/tools.js'),

        'admin/main': path.join(srcPath, 'admin/main.scss'),
    },
    output: {
        ...defaultConfig.output,
        path: distPath,
        filename: '[name].js'
    },
    plugins: [
        ...defaultConfig.plugins,
        //new CopyWebpackPlugin({
        //    patterns: getCopyPatterns()
        //})
    ]
};
