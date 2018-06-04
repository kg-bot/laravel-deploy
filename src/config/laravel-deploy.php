<?php
/**
 * Created by PhpStorm.
 * User: kgbot
 * Date: 6/4/18
 * Time: 1:37 AM
 */

return [

    'routes' => [

        'prefix'     => env( 'LARAVEL_DEPLOY_PREFIX', 'laravel-deploy' ),
        'middleware' => ( env( 'LARAVEL_DEPLOY_MIDDLEWARE' ) ) ? explode( ',', env( 'LARAVEL_DEPLOY_MIDDLEWARE' ) )
            : [],
    ]
    ,
    'events' => [

        'channel' => env( 'LARAVEL_DEPLOY_EVENTS_CHANNEL', '' ),
    ],
];