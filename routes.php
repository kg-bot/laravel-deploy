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

    Route::get( 'deploy', 'DeployController@request' )->name( 'laravel-deploy.deploy.request' );
} );
