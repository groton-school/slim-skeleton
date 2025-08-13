import bundle from '@battis/webpack';

export default bundle.fromTS.toSPA({
  root: import.meta.dirname,
  appName: 'slim-skeleton/gae/lti-tool',
  entry: './src/SPA/index.ts',
  template: './views/SPA',
  module: {
    rules: [
      {
        test: /\.ejs$/,
        loader: 'html-loader',
        options: {
          // don't process the sources in ejs files -- they may not exist (yet)!
          sources: false
        }
      }
    ]
  },
  externals: {
    bootstrap: 'bootstrap'
  },
  output: { path: 'public' }
});
