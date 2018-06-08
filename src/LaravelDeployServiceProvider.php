<?php
/**
 * Created by PhpStorm.
 * User: kgbot
 * Date: 6/3/18
 * Time: 11:48 PM
 */

namespace KgBot\LaravelDeploy;


use Illuminate\Support\ServiceProvider;
use KgBot\LaravelDeploy\Console\Commands\NewClient;

class LaravelDeployServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '//config/laravel-deploy.php', 'laravel-deploy'
        );
    }

    public function boot()
    {
        if ( $this->app->runningInConsole() ) {
            $this->commands( [
                NewClient::class,
            ] );
        }

        $this->publishes( [
            __DIR__ . '/config/laravel-deploy.php' => config_path( 'laravel-deploy.php' ),
        ], 'config' );

        $this->publishes( [
            __DIR__ . '/database/migrations/' => database_path( 'migrations' ),
        ], 'migrations' );

        $this->loadRoutesFrom( __DIR__ . '/../routes.php' );

        $this->publishes( [

            __DIR__ . '/resources/assets/' => resource_path( 'vendor/laravel-deploy' ),
        ], 'resources' );

        $this->loadViewsFrom( __DIR__ . '/resources/views', 'laravel-deploy' );
    }
}