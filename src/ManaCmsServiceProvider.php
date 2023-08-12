<?php

namespace Mana\Cms;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class ManaCmsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        
        if ($this->app->runningInConsole()) {
            // $this->publishes([
            //     __DIR__.'/../stubs/database/migrations' => database_path('migrations'),
            // ], 'manacms-migrations');
    
            // $this->publishes([
            //     __DIR__.'/../stubs/config/menu.php' => config_path('menu.php'),
            //     __DIR__.'/../stubs/config/moduletask.php' => config_path('moduletask.php'),
            // ], 'manacms-config');

            $this->commands([
                Console\InstallCommand::class,
            ]);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [Console\InstallCommand::class];
    }
}
