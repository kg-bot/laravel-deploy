<?php
/**
 * Created by PhpStorm.
 * User: kgbot
 * Date: 6/3/18
 * Time: 11:48 PM.
 */

namespace KgBot\LaravelDeploy;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use KgBot\LaravelDeploy\Console\Commands\NewClient;
use KgBot\LaravelLocalization\Facades\ExportLocalizations;

class LaravelDeployServiceProvider extends ServiceProvider
{
    public function register()
    {
        /*
         * We define those globals because we need them in LogParser
         */
        if (! defined('REGEX_DATE_PATTERN')) {
            define('REGEX_DATE_PATTERN', '\d{4}(-\d{2}){2}'); // YYYY-MM-DD
        }
        if (! defined('REGEX_TIME_PATTERN')) {
            define('REGEX_TIME_PATTERN', '\d{2}(:\d{2}){2}'); // HH:MM:SS
        }
        if (! defined('REGEX_DATETIME_PATTERN')) {
            define(
                'REGEX_DATETIME_PATTERN',
                REGEX_DATE_PATTERN.' '.REGEX_TIME_PATTERN // YYYY-MM-DD HH:MM:SS
            );
        }
    }

    public function boot()
    {
        View::composer('laravel-deploy::dashboard', function ($view) {
            return $view->with([
                'user'     => auth()->user(),
                'messages' => ExportLocalizations::export()->toFlat(),
            ]);
        });

        if ($this->app->runningInConsole()) {
            $this->commands([
                NewClient::class,
            ]);
        }

        /*
         * Config
         */
        $this->mergeConfigFrom(
            __DIR__.'//config/laravel-deploy.php', 'laravel-deploy'
        );

        $this->publishes([
            __DIR__.'/config/laravel-deploy.php' => config_path('laravel-deploy.php'),
        ], 'config');

        /*
         * Migrations
         */
        $this->publishes([
            __DIR__.'/database/migrations/' => database_path('migrations'),
        ], 'migrations');

        /*
         * Routes
         */
        $this->loadRoutesFrom(__DIR__.'/../routes.php');

        /*
         * Assets
         */
        $this->publishes([

            __DIR__.'/resources/assets/' => resource_path('assets/vendor/laravel-deploy'),
        ], 'assets');

        /*
         * Localization
         */
        $this->loadTranslationsFrom(__DIR__.'/resource/lang', 'laravel-deploy');
        $this->publishes([

            __DIR__.'/resources/lang' => resource_path('lang/vendor/laravel-deploy'),
        ], 'lang');

        /*
         * Views
         */
        $this->loadViewsFrom(__DIR__.'/resources/views', 'laravel-deploy');

        $this->publishes([

            __DIR__.'/resources/views' => resource_path('views/vendor/laravel-deploy'),
        ], 'views');
    }
}
