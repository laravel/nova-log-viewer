let mix = require('laravel-mix')

mix.extend('nova', new require('laravel-nova-devtool'))

mix
  .setPublicPath('dist')
  .js('resources/js/tool.js', 'dist')
  .vue({ version: 3 })
  .nova('laravel/nova-log-viewer')
