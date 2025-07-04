const mix = require('laravel-mix');
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

// Enable source maps for better error reporting
if (!mix.inProduction()) {
  mix.sourceMaps();
}

mix.js('resources/js/app.js', 'public/js')
   .vue({ version: 3 })
   .postCss('resources/css/app.css', 'public/css', [require("tailwindcss")])

mix.webpackConfig({
  output: {
    hashFunction: "sha256" // or "sha256" if "xxhash64" is not available
  },
  devtool: mix.inProduction() ? false : 'source-map'
});

// Fix the Vue loader configuration
mix.override((webpackConfig) => {
  const vueRule = webpackConfig.module.rules.find(
    (rule) => rule.test && rule.test.toString().includes('vue')
  );
  
  if (vueRule && vueRule.use) {
    // If the rule has a 'use' property, modify it properly
    const vueLoader = Array.isArray(vueRule.use) 
      ? vueRule.use.find(loader => loader.loader && loader.loader.includes('vue-loader')) 
      : (typeof vueRule.use === 'object' ? vueRule.use : null);
    
    if (vueLoader && vueLoader.options) {
      vueLoader.options.compilerOptions = vueLoader.options.compilerOptions || {};
      vueLoader.options.compilerOptions.whitespace = 'preserve';
    }
  } else if (vueRule) {
    // If it has no 'use' property, then we can set options directly
    vueRule.options = vueRule.options || {};
    vueRule.options.compilerOptions = vueRule.options.compilerOptions || {};
    vueRule.options.compilerOptions.whitespace = 'preserve';
  }
});
