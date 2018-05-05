<?php

namespace Mphillipson\Multiget\Providers;

use Mphillipson\Multiget\Console\Commands\Download;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class MultigetServiceProvider extends ServiceProvider
{
    /**
     * The Laravel application instance.
     *
     * @var Application
     */
    protected $app;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/multiget.php' => config_path('multiget.php')
        ], 'config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                Download::class
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/multiget.php', 'multiget');
    }
}
