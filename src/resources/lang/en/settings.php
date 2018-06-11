<?php
/**
 * Created by PhpStorm.
 * User: kgbot
 * Date: 6/10/18
 * Time: 11:03 PM
 */

return [

    'deployments' => [

        'http'       => [

            'deploy_now'          => [

                'success' => 'Deployment has been started, please check back soon for details.',
                'error'   => 'We can\'t run deployment script right now.',
            ],
            'change_quick_deploy' => [

                'success' => 'Your quick deploy settings have been changed.',
                'error'   => 'We can\'t change quick deploy settings right now.',
            ],
        ],
        'deploy_now' => [

            'modal' => [

                'labels' => [

                    'client' => 'Select Client',
                ],
                'title'  => 'Choose Client to Deploy As',
            ],
        ],

        'quick_deploy' => [

            'modal' => [

                'labels' => [

                    'client' => 'Select Client',
                ],
                'title'  => 'Choose Client to Set Quick Deploy',
            ],
        ],
    ],
];