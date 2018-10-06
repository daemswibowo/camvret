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
        $this->commands(
            'Daemswibowo\Camvret\Commands\Install'
        );
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
        // $this->publishes([
        //     __DIR__.'/../config/camvret.php' => config_path('camvret.php'),
        // ], 'camvret.config');

        // Publishing the views.
        $this->publishes([
            __DIR__.'/../resources' => base_path('resources'),
            __DIR__.'/../app' => base_path('app'),
            __DIR__.'/../routes' => base_path('routes'),
            __DIR__.'/../database' => base_path('database'),
            __DIR__.'/../package.json' => base_path('package.json'),
            __DIR__.'/../webpack.mix.js' => base_path('webpack.mix.js'),
        ], 'camvret');

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
