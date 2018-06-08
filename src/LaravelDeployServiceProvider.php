<?php
/**
 * Created by PhpStorm.
 * User: kgbot
 * Date: 6/3/18
 * Time: 11:48 PM
 */

namespace KgBot\LaravelDeploy;


use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use KgBot\LaravelDeploy\Console\Commands\NewClient;

class LaravelDeployServiceProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        View::composer( 'laravel-deploy::dashboard', function ( $view ) {

            return $view->with( [ 'user' => auth()->user() ] );
        } );

        if ( $this->app->runningInConsole() ) {
            $this->commands( [
                NewClient::class,
            ] );
        }

        /**
         * Config
         */
        $this->mergeConfigFrom(
            __DIR__ . '//config/laravel-deploy.php', 'laravel-deploy'
        );

        $this->publishes( [
            __DIR__ . '/config/laravel-deploy.php' => config_path( 'laravel-deploy.php' ),
        ], 'config' );

        /**
         * Migrations
         */
        $this->publishes( [
            __DIR__ . '/database/migrations/' => database_path( 'migrations' ),
        ], 'migrations' );

        /**
         * Routes
         */
        $this->loadRoutesFrom( __DIR__ . '/../routes.php' );

        /**
         * Assets
         */
        $this->publishes( [

            __DIR__ . '/resources/assets/' => resource_path( 'assets/vendor/laravel-deploy' ),
        ], 'resources' );

        /**
         * Localization
         */
        $this->loadTranslationsFrom( __DIR__ . '/resource/lang', 'laravel-deploy' );
        $this->publishes( [

            __DIR__ . '/resources/lang' => resource_path( 'lang/vendor/laravel-deploy' ),
        ], 'lang' );

        /**
         * Views
         */
        $this->loadViewsFrom( __DIR__ . '/resources/views', 'laravel-deploy' );
    }
}