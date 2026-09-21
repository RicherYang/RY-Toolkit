const path = require('path');

const defaultConfig = require('@wordpress/scripts/config/webpack.config', true);
const { fromProjectRoot } = require('@wordpress/scripts/utils/file', true);

const srcPath = fromProjectRoot('assets-src');
const distPath = fromProjectRoot('assets');

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
    }
};
