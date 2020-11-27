const path = require('path');
const StylelintPlugin = require('stylelint-webpack-plugin');

module.exports = {
  configureWebpack: {
    resolve: {
      alias: {
        // Public: path.resolve(__dirname, 'public/'),
        Elements: path.resolve(__dirname, 'src/components/elements/'),
        Parts: path.resolve(__dirname, 'src/components/parts/')
      }
    },
    plugins: [new StylelintPlugin()]
  },
  // outputDir: 'dist',
  // assetsDir: 'public'
};
