const mix = require('laravel-mix');

require('laravel-mix-purgecss');

mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        require('tailwindcss'),
    ]);

if (mix.inProduction()) {
    mix.purgeCss({
        whitelistPatterns: [/^ql-/, /^multiselect/]
    });
}
