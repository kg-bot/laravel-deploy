<?php
/**
 * Created by PhpStorm.
 * User: kgbot
 * Date: 6/4/18
 * Time: 12:18 AM
 */

use Illuminate\Support\Facades\Cache;
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
    'middleware' => array_merge( [ 'web', 'auth' ], config( 'laravel-deploy.front.routes.middleware' ) ),
    'namespace'  => config( 'laravel-deploy.front.routes.namespace' ),
], function () {

    Route::get( '', 'DashboardController@index' )->name( 'laravel-deploy.dashboard' );
} );

/**
 * Localization
 */
Route::get( '/js/lang.js', function () {
    $strings = Cache::rememberForever( 'lang.js', function () {

        function dirToArray( $dir )
        {
            $result = [];

            $cdir = scandir( $dir );
            foreach ( $cdir as $key => $value ) {
                if ( !in_array( $value, [ ".", ".." ] ) ) {
                    if ( is_dir( $dir . DIRECTORY_SEPARATOR . $value ) ) {
                        $result[ $value ] = dirToArray( $dir . DIRECTORY_SEPARATOR . $value );
                    } else {
                        $result[] = $value;
                    }
                }
            }

            return $result;
        }

        $files = dirToArray( resource_path( 'lang' ) );

        $strings = [];

        foreach ( $files as $file ) {
            $name             = basename( $file, '.php' );
            $strings[ $name ] = require $file;
        }

        return $strings;
    } );

    header( 'Content-Type: text/javascript' );
    echo( 'window.i18n = ' . json_encode( $strings ) . ';' );
    exit();
} )->name( 'assets.lang' );
