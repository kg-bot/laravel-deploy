<?php
/**
 * Created by PhpStorm.
 * User: kgbot
 * Date: 6/4/18
 * Time: 1:37 AM
 */

return [

    'routes'     => [

        /**
         * Route prefix, example of route http://localhost/laravel-deploy/deploy?_token=#################
         *
         */
        'prefix'     => env( 'LARAVEL_DEPLOY_PREFIX', 'laravel-deploy' ),

        /**
         * Middleware used on webhook routes, default middleware is KgBot\LaravelDeploy\Http\Middleware\IsTokenValid
         *
         * You can add more middleware with .env directive, example LARAVEL_DEPLOY_MIDDLEWARE=webhook,auth:api, etc.
         *
         * Don't use space in .env directive after ,
         */
        'middleware' => ( env( 'LARAVEL_DEPLOY_MIDDLEWARE' ) ) ? explode( ',', env( 'LARAVEL_DEPLOY_MIDDLEWARE' ) )
            : [],
    ],
    'events'     => [

        /**
         * This package emits some events before and after it run's deployment script
         *
         * Here you can change channel on which events will be broadcast
         */
        'channel' => env( 'LARAVEL_DEPLOY_EVENTS_CHANNEL', '' ),
    ],

    /**
     * This packages is doing all of it's work in a Job and here you change queue on which it will execute jobs
     */
    'queue'      => env( 'LARAVEL_DEPLOY_QUEUE', 'default' ),

    /**
     * With this directive you can enable/disable this package
     */
    'run_deploy' => env( 'LARAVEL_DEPLOY_RUN', true ),

    /**
     * Detailed description is provided inside a README.md file
     *
     * Here you set your default server user and password which will be used to run deploy script
     */
    'user'       => [

        'username' => env( 'LARAVEL_DEPLOY_USERNAME', 'www-data' ),
        'password' => env( 'LARAVEL_DEPLOY_PASSWORD', '' ),
    ],
    /**
     * Everything related to front-end part of package should go here
     */
    'front'      => [

        'title' => 'Laravel Deploy - Dashboard',

        'routes' => [

            'middleware' => ( env( 'LARAVEL_DEPLOY_FRONT_MIDDLEWARE' ) ) ?
                explode( ',', env( 'LARAVEL_DEPLOY_FRONT_MIDDLEWARE' ) ) :
                [],

            'namespace' => 'KgBot\LaravelDeploy\Http\Controllers\Front',
        ],
    ],
];