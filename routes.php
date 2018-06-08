<?php
/**
 * Created by PhpStorm.
 * User: kgbot
 * Date: 6/4/18
 * Time: 12:18 AM
 */

use KgBot\LaravelDeploy\Http\Middleware\IsValidToken;

Route::group( [
    'prefix'     => config( 'laravel-deploy.routes.prefix' ),
    'middleware' => array_merge( [ IsValidToken::class ], config( 'laravel-deploy.routes.middleware' ) ),
    'namespace'  => 'KgBot\LaravelDeploy\Http\Controllers',

], function () {

    Route::any( 'deploy', 'DeployController@request' )->name( 'laravel-deploy.deploy.request' );
} );

/**
 * Front-end routes
 */
Route::group( [

    'prefix'     => config( 'laravel-deploy.routes.prefix' ) . '/dashboard',
    'middleware' => array_merge( [ 'web', 'auth' ], config( 'laravel-deploy.routes.front.middleware' ) ),
    'namespace'  => 'KgBot\LaravelDeploy\Http\Controllers\Front',
], function () {

    Route::get( '', 'DashboardController@index' )->name( 'laravel-deploy.dashboard' );
} );
