<?php

namespace RemoteClient\RemoteSoap;

use Illuminate\Support\ServiceProvider;

class RemoteSoapServiceProvider extends ServiceProvider
{

    protected $defer = true;
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('RemoteSoapClient',function ($app){
            return new RemoteSoapClient($app['config']);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
             __DIR__.'/config/remote.php' => config_path('remote.php')
         ]);
    }

    public function provides()
    {
        return ['RemoteSoapClient'];
    }
}
