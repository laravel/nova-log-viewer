<?php

use Illuminate\Support\Facades\Route;
use Laravel\Nova\LogViewer\Http\Controllers\Pages\LogViewerController;

/*
|--------------------------------------------------------------------------
| Tool Routes
|--------------------------------------------------------------------------
|
| Here is where you may register Inertia routes for your tool. These are
| loaded by the ServiceProvider of the tool. The routes are protected
| by your tool's "Authorize" middleware by default. Now - go build!
|
*/

Route::get('/', LogViewerController::class);
