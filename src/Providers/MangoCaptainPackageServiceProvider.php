<?php

namespace CMS\Backpack\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class MangoCaptainPackageServiceProvider extends ServiceProvider
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     *
     */
    public $routeFilePath = '/routes/backpack/mangocaptainroute.php';
    public function boot()
    {
        // use the vendor configuration file as fallback
        $this->mergeConfigFrom(
            __DIR__.'/config/backpack',
            'backpack'
        );
        // publish config file
        $this->publishes([__DIR__.'/config' => config_path()], 'config');

        // publish translation files
        $this->publishes([__DIR__.'/resources/backpack' => resource_path('views/vendor/backpack')], 'views');

        // publish migration from Backpack 4.0 to Backpack 4.1
        $this->publishes([__DIR__.'/database/migrations' => database_path('migrations')], 'migrations');

        $this->publishes([__DIR__."/database/migrations"=> database_path("seeders")],"seeders");
    }
    public function setupRoutes(Router $router)
    {
        // by default, use the routes file provided in vendor
        $routeFilePathInUse = __DIR__.$this->routeFilePath;

        // but if there's a file with the same name in routes/backpack, use that one
        if (file_exists(base_path().$this->routeFilePath)) {
            $routeFilePathInUse = base_path().$this->routeFilePath;
        }

        $this->loadRoutesFrom($routeFilePathInUse);
    }
    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(MangoCaptainPackageServiceProvider::class);
    }
}
