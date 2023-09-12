<?php

namespace tayyabtahir71\MT5WebApi\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class InspirationProvider extends ServiceProvider
{
    /**
     * Check to see if we're using lumen or laravel.
     *
     * @return bool
     */
    public function isLumen()
    {
        $lumenClass = 'Laravel\Lumen\Application';
        return ($this->app instanceof $lumenClass);
    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {

        $this->publishConfig();
        $this->publishViews();

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }

        //$this->loadViewsFrom(__DIR__.'/views', 'MT5WebApi');

        // Install the routes
        $this->installRoutes();
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/metaquotes.php', 'mt5webapi');

        // Register the service the package provides.
        $this->app->singleton('mt5webapi', function ($app) {
            return new MT5WebApi;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['mt5webapi'];
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
            __DIR__ . '/../config/metaquotes.php' => config_path('metaquotes.php'),
        ], 'mt5webapi.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/aemaddin'),
        ], 'mt5webapi.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/aemaddin'),
        ], 'mt5webapi.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/aemaddin'),
        ], 'mt5webapi.views');*/

        // Registering package commands.
        // $this->commands([]);
    }

    /**
     * Publish the configuration files
     *
     * @return void
     */
    protected function publishConfig()
    {
        if (!$this->isLumen()) {
            $this->publishes([
                __DIR__.'/../config/metaquotes.php' => config_path('metaquotes.php')
            ], 'config');
        }
    }

    /**
     * Publish the views
     *
     * @return void
     */
    protected function publishViews()
    {
        if (!$this->isLumen()) {
            $this->loadViewsFrom(__DIR__.'/../views', 'MT5WebApi');

            $this->publishes([
                __DIR__.'/../views' => base_path('resources/views/vendor/mt5webapi'),
            ]);
        }
    }

    protected function installRoutes()
    {
        $config = $this->app['config']->get('metaquotes.route', []);
        $config['namespace'] = 'tayyabtahir71\MT5WebApi';

        if (!$this->isLumen()) {
            Route::group($config, function () {
                Route::get('/', 'Controllers\MT5Controller@index')->name('MT5WebApi_home');
                Route::get('account/{account}/{data?}', 'Controllers\MT5Controller@account')->name('MT5WebApi_account');
                Route::get('user/{user}/{data?}', 'Controllers\MT5Controller@user')->name('MT5WebApi_user');
                Route::get('positions/{user}/{data?}', 'Controllers\MT5Controller@positions')->name('MT5WebApi_positions');
                Route::get('orders/{user}/{data?}', 'Controllers\MT5Controller@orders')->name('MT5WebApi_orders');
                Route::get('deals/{user}/{data?}', 'Controllers\MT5Controller@deals')->name('MT5WebApi_deals');
                Route::get('symbols/{user}/{data?}', 'Controllers\MT5Controller@symbols')->name('MT5WebApi_symbols');
                Route::get('balance/{user}', 'Controllers\MT5Controller@balance')->name('MT5WebApi_balance');

            });
        } else {
            $app = $this->app;
            $app->group($config, function () use ($app) {
                // @todo finish up these routes
            });
        }

    }
}