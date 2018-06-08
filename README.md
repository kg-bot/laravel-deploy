[![Latest Stable Version](https://poser.pugx.org/kg-bot/laravel-deploy/v/stable)](https://packagist.org/packages/kg-bot/laravel-deploy)
[![Total Downloads](https://poser.pugx.org/kg-bot/laravel-deploy/downloads)](https://packagist.org/packages/kg-bot/laravel-deploy)
[![Latest Unstable Version](https://poser.pugx.org/kg-bot/laravel-deploy/v/unstable)](https://packagist.org/packages/kg-bot/laravel-deploy)
[![License](https://poser.pugx.org/kg-bot/laravel-deploy/license)](https://packagist.org/packages/kg-bot/laravel-deploy)
[![Monthly Downloads](https://poser.pugx.org/kg-bot/laravel-deploy/d/monthly)](https://packagist.org/packages/kg-bot/laravel-deploy)
[![Daily Downloads](https://poser.pugx.org/kg-bot/laravel-deploy/d/daily)](https://packagist.org/packages/kg-bot/laravel-deploy)

# Laravel Deploy

Laravel deploy package, used to automatically deploy project from GIT VCS that supports webhooks.

## Installing

Just require this package with composer.

```
composer require kg-bot/laravel-deploy
```

Before you can use this package you have to export and run migrations and create clients for each VCS site or repository 
```
php artisan vendor:publish --provider=KgBot\LaravelDeploy\LaravelDeployServiceProvider --tag=migrations
php artisan migrate
```

You can create new client with artisan command (Web panel should be in next release)
```
php artisan laravel-deploy:new-client {name} {token} {script_source} {source}
```

#### Explanation of command parameters

+ `name` - Name of the client (eg. GitHub)
+ `token` - Token you want to check when validating request, it will be encrypted
+ `script_source` - Name of the .sh script that will be executed on deploy request ( this script needs to be placed in project root )
+ `source` - Can be anything and might be used in later versions of app, preferably set this to domain from where you expect request or repository URL


Because deploy is initiated from HTTP request user executing .sh script would be www-data and I find it cumbersome for non Linux or Server oriented developers to tweak with user permissions and server privileges.  

This can be changed with some .env directives, you should create .sh script with your default server user and set rwx permissions for that user.  

Next thing you need to do is add `LARAVEL_DEPLOY_USERNAME` to .env file and set it to your default user (same user from your SSH/CLI).  

Also you need to set `LARAVEL_DEPLOY_PASSWORD` .env directive to match your default SSH/CLI login password.  

##### Example

If you login to your server with `ssh forge@123.213.65.1` and you enter `temp123` as your password then you would set `LARAVEL_DEPLOY_USERNAME=forge` and `LARAVEL_DEPLOY_PASSWORD=temp123` inside .env file. 

Laravel Deploy is enabled by default, if you want to disable it add `LARAVEL_DEPLOY_RUN=false` to your .env file.

### Laravel 5.5+

Laravel 5.5 uses Package Auto-Discovery, so doesn't require you to manually add the ServiceProvider.

If you don't use auto-discovery, add the ServiceProvider to the providers array in config/app.php
``` 
KgBot\LaravelDeploy\LaravelDeployServiceProvider::class
```

## Settings and configuration

You can export config by running 

```
php artisan vendor:publish --provide=KgBot\LaravelDeploy\LaravelDeployServiceProvider --tag=config
```

We have already explained some of this package config directives but that's not all.  

```
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
];
```

## Routing

This package exposes some routes (only one for now but there will be more of them in next versions)

# How to setup webhooks in GIT VCS sites

### GitHub

Let's say that your repository is located at [https://github.com/kg-bot/laravel-deploy], you would go to [https://github.com/kg-bot/laravel-deploy/settings/hooks/new].  

There you will get screen like this ![GitHub Webhook Create](https://i.imgur.com/CZ2qx4u.png)  

In the Payload URL you need to enter webhook URL which *must* be [http://example.com/laravel-deploy/deploy+_token=#####################] (it can be http or https, depend's on your server SSL settings), where `example.com` should be your domain and `laravel-deploy` should be the prefix you set in configuration, also the token part needs to match hashed value you got when creating new client.  

Other parts are not important for now, in next versions this will be probably need to be changed because there will be different code for each site (drivers).

### BitBucket

Everything is same as GitHub except links and field names.  

Link to create new webhook should be [https://bitbucket.org/:USERNAME:/:REPO:/admin/addon/admin/bitbucket-webhooks/bb-webhooks-repo-admin]  

It looks like this ![BitBucket Webhook Create](https://i.imgur.com/5Zw4Obl.png), Title can be anything, URL should be same as in GitHub settings, you must check Active and you can check Skip certificate verification for now (until next versions)

## Front-end requirements and installation

Front part of this package works as Vue.js SPA and it heavily depends on npm packages

``
npm install bootstrap-vue vue-resource vue-router vue-toasted vue-awesome lang.js lodash change-case datejs
``
Then you have to export assets from this package, this will add some JavaScript and SASS files inside your resources/assets/vendor/laravel-deploy directory

```

php artisan vendor:publish --provider=KgBot\\LaravelDeploy\\LaravelDeployServiceProvider --tag=assets
```  

After this you have to alter webpack.mix.js and add this at the end of file
```
/**
 * Laravel deploy assets
 */
mix.js( 'resources/assets/vendor/laravel-deploy/js/laravel-deploy.js', 'public/assets/vendor/laravel-deploy/js' )
   .extract( [ 'vue' ] )
   .sass( 'resources/assets/vendor/laravel-deploy/sass/laravel-deploy.scss', 'public/assets/vendor/laravel-deploy/css' );
```
You can now go to `http://localhost/laravel-deploy/dashboard` or any other URL if you have changed route prefix, just add `/dashboard` at the end.

## Proposals, comments, feedback

Everything of this is highly welcome and appreciated

## To-Do

+ Web panel
+ Driver for each site, system
+ Notifications on success, failure (slack, sms, mail, etc.)
+ Improved .sh script execution (without using username and password directly in command because it can be later seen in bash_history)
+ Improved client validation and recognition  

Anything else you can think of please leave me comments, mail me, create issue, whatever you prefer.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details
