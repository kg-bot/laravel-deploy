<?php
/**
 * Created by PhpStorm.
 * User: kgbot
 * Date: 6/10/18
 * Time: 1:35 PM
 */

return [

    'new'  => [
        'title' => 'Add New Client',

        'labels'       => [

            'name'        => 'Name:',
            'source'      => 'Source:',
            'token'       => 'Token:',
            'script'      => 'Script name:',
            'auto_deploy' => 'Auto deploy',
        ],
        'placeholders' => [

            'name'   => 'eg. GitHub',
            'source' => 'eg. https://github.com',
            'script' => 'eg. github_deploy.sh',
            'token'  => 'eg. test123',
        ],
    ],
    'edit' => [
        'title' => 'Edit Client',

        'labels'       => [

            'name'        => 'Name:',
            'source'      => 'Source:',
            'token'       => 'Token:',
            'script'      => 'Script name:',
            'auto_deploy' => 'Auto deploy',
        ],
        'placeholders' => [

            'name'   => 'eg. GitHub',
            'source' => 'eg. https://github.com',
            'script' => 'eg. github_deploy.sh',
            'token'  => 'eg. test123',
        ],
    ],
];