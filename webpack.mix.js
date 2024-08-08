let mix = require('laravel-mix')

mix.extend('nova', new require('./vendor/laravel/nova/nova.mix'))

mix
  .setPublicPath('dist')
  .js('resources/js/tool.js', 'js')
  .vue({ version: 3 })
  .nova('laravel/nova-log-viewer')
