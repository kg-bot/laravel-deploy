<?php
/**
 * Created by PhpStorm.
 * User: kgbot
 * Date: 6/4/18
 * Time: 12:18 AM.
 */
use KgBot\LaravelDeploy\Http\Middleware\IsValidToken;

Route::group([
    'prefix'     => config('laravel-deploy.routes.prefix'),
    'middleware' => array_merge([IsValidToken::class], config('laravel-deploy.routes.middleware')),
    'namespace'  => 'KgBot\LaravelDeploy\Http\Controllers',

], function () {
    Route::any('deploy', 'DeployController@request')->name('laravel-deploy.deploy.request');
});

/*
 * Front-end routes
 */
Route::group([

    'prefix'     => config('laravel-deploy.routes.prefix').'/dashboard',
    'middleware' => array_merge(['web', 'auth'], config('laravel-deploy.front.routes.middleware')),
    'namespace'  => config('laravel-deploy.front.routes.namespace'),
], function () {
    Route::get('', 'DashboardController@index')->name('laravel-deploy.dashboard');
});

/*
 * Ajax routes
 */
Route::group([

    'prefix'     => config('laravel-deploy.routes.prefix').'/ajax',
    'middleware' => array_merge(['web', 'auth'], config('laravel-deploy.front.routes.ajax.middleware')),
    'namespace'  => config('laravel-deploy.front.routes.ajax.namespace'),
], function () {
    Route::post('/clients/{client}/status', 'ClientsController@changeStatus')
         ->name('laravel-deploy.ajax.clients.status');

    Route::post('/clients/{client}/auto-deploy', 'ClientsController@changeAutoDeploy')
         ->name('laravel-deploy.ajax.clients.auto_deploy');

    Route::resource('/clients', 'ClientsController', [

        'only'  => ['index', 'store', 'update', 'destroy'],
        'names' => [

            'index'   => 'laravel-deploy.ajax.clients.index',
            'store'   => 'laravel-deploy.ajax.clients.store',
            'update'  => 'laravel-deploy.ajax.clients.update',
            'destroy' => 'laravel-deploy.ajax.clients.destroy',
        ],
    ]);

    /*
     * Settings routes
     */
    Route::group(['prefix' => 'settings'], function () {
        Route::get('last-log', 'SettingsController@lastLog')->name('laravel-deploy.ajax.settings.last_log');
        Route::get('logs', 'SettingsController@allLogs')->name('laravel-deploy.ajax.settings.logs');
        Route::get('index', 'SettingsController@index')->name('laravel-deploy.ajax.settings.index');

        /*
         * Deployments routes
         */
        Route::group(['prefix' => 'deployments'], function () {
            Route::post('deploy/now/{client}', 'SettingsController@deployNow')
                 ->name('laravel-deploy.ajax.settings.deployments.deploy_now');

            /*
             * Deployment script routes
             */
            Route::get('scripts/{client}', 'ClientScriptController@fetch')
                 ->name('laravel-deploy.ajax.settings.deployments.scripts.fetch');

            Route::post('scripts/{client}', 'ClientScriptController@save')
                 ->name('laravel-deploy.ajax.settings.deployments.scripts.save');
        });
    });
});
