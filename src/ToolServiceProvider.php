<?php

namespace Laravel\Nova\LogViewer;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\LogViewer\Http\Middleware\Authorize;
use Laravel\Nova\Nova;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->booted(function () {
            $this->routes();
        });

        Nova::serving(function (ServingNova $event) {
            Nova::translations([
                "Logs" => "Logs",
                "Log Viewer" => "Log Viewer",
                "Select a log file..." => "Select a log file...",
                "Refresh" => "Refresh",
                "Start polling" => "Start polling",
                "Stop polling" => "Stop polling",
                "Scroll to top" => "Scroll to top",
                "Scroll to bottom" => "Scroll to bottom",
                ":number lines" => ":number lines",
                ":number line" => ":number line"
            ]);
        });
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Nova::router(['nova', Authorize::class], 'logs')
            ->group(__DIR__ . '/../routes/inertia.php');

        Route::middleware(['nova', Authorize::class])
            ->prefix('nova-vendor/logs')
            ->group(__DIR__ . '/../routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
