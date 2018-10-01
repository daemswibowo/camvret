<?php

namespace Daemswibowo\Camvret;

use Illuminate\Support\ServiceProvider;

class CamvretServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'daemswibowo');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'daemswibowo');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/camvret.php', 'camvret');

        // Register the service the package provides.
        $this->app->singleton('camvret', function ($app) {
            return new Camvret;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['camvret'];
    }
    
    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/camvret.php' => config_path('camvret.php'),
        ], 'camvret.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/daemswibowo'),
        ], 'camvret.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/daemswibowo'),
        ], 'camvret.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/daemswibowo'),
        ], 'camvret.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
