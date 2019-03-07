let mix = require('laravel-mix')
require('laravel-mix-purgecss')

mix.js('resources/js/app.js', 'public/js')
  .postCss('resources/css/app.css', 'public/css')
  .options({
    postCss: [
      require('postcss-import')(),
      require('tailwindcss')('tailwind.js'),
      require('postcss-cssnext')({
        // Mix adds autoprefixer already, don't need to run it twice
        features: { autoprefixer: false }
      }),
    ]
  })
  .purgeCss()