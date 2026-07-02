/**
 * Great Endured Technology — Local Build Script
 * Bundles and minifies JS and CSS using esbuild.
 * Governed by stack decisions in Resource.md (Section 3)
 */

const esbuild = require('esbuild');
const path = require('path');

const isWatch = process.argv.includes('--watch');

async function run() {
  const options = {
    entryPoints: [
      { in: path.join(__dirname, '../../src/js/main.js'), out: 'js/app' },
      { in: path.join(__dirname, '../../src/css/main.css'), out: 'css/app' }
    ],
    bundle: true,
    minify: true,
    sourcemap: false,
    outdir: path.join(__dirname, '../../Front/static'),
    logLevel: 'info',
    loader: {
      '.woff2': 'file',
      '.woff': 'file',
      '.ttf': 'file',
      '.svg': 'file'
    }
  };

  try {
    if (isWatch) {
      console.log('Starting watch mode...');
      const ctx = await esbuild.context(options);
      await ctx.watch();
    } else {
      console.log('Compiling production assets...');
      const result = await esbuild.build(options);
      console.log('Compilation successful!');
    }
  } catch (error) {
    console.error('Compilation failed:', error);
    process.exit(1);
  }
}

run();
