<?php

namespace Rakshitbharat\Pythoninphp;

use Illuminate\Support\ServiceProvider;

class PythonServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/pythoninphp.php' => config_path('pythoninphp.php'),
            ], 'pythoninphp-config');
        }
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/pythoninphp.php', 'pythoninphp'
        );

        $this->app->singleton('pythoninphp', function ($app) {
            return new PythonRunner(
                config('pythoninphp.executable'),
                config('pythoninphp.timeout')
            );
        });
    }
}
