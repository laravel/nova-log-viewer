let mix = require('laravel-mix')
let nova = require('laravel-nova-devtool')

mix.extend('nova', new nova)

mix
  .setPublicPath('dist')
  .js('resources/js/tool.js', 'dist')
  .vue({ version: 3 })
  .nova('laravel/nova-log-viewer')
