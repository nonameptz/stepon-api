<?php

namespace Api\User;

use Api\User\V1\Rpc\Login\Factory\LoginControllerFactory;
use Api\User\V1\Rpc\Login\LoginController;
use Api\User\V1\Rpc\Logout\Factory\LogoutControllerFactory;
use Api\User\V1\Rpc\Logout\LogoutController;

return [
    'router'                 => [
        'routes' => [
            'api.user'       => [
                'child_routes' => [
                    'login'  => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => '/login',
                            'defaults' => [
                                'controller' => LoginController::class,
                                'action'     => 'login',
                            ],
                        ],
                    ],
                    'logout' => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => '/logout',
                            'defaults' => [
                                'controller' => LogoutController::class,
                                'action'     => 'logout',
                            ],
                        ],
                    ],
                ],
            ],
            'api.user.oauth' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/oauth',
                    'defaults' => [
                        'controller' => LoginController::class,
                        'action'     => 'login',
                    ],
                ],
            ],
        ],
    ],
    'zf-rpc'                 => [
        LoginController::class  => [
            'service_name' => 'Login',
            'http_methods' => [
                0 => 'POST',
            ],
            'route_name'   => 'api.user/login',
        ],
        LogoutController::class => [
            'service_name' => 'logout',
            'http_methods' => [
                0 => 'POST',
            ],
            'route_name'   => 'api.user/logout',
        ],
    ],
    'zf-content-negotiation' => [
        'controllers'            => [
            LoginController::class  => 'HalJson',
            LogoutController::class => 'HalJson',
        ],
        'accept_whitelist'       => [
            LoginController::class  => [
                0 => 'application/vnd.api-login.user.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            LogoutController::class => [
                0 => 'application/vnd.api-logout.user.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
        ],
        'content_type_whitelist' => [
            LoginController::class => [
                0 => 'application/vnd.api-login.user.v1+json',
                1 => 'application/json',
            ],
        ],
    ],
    'zf-mvc-auth'            => [
        'authorization' => [
            LoginController::class  => [
                'actions' => [
                    'login' => [
                        'POST' => false,
                    ],
                ],
            ],
            LogoutController::class => [
                'actions' => [
                    'logout' => [
                        'POST' => true,
                    ],
                ],
            ],
        ],
    ],
    'controllers'            => [
        'factories' => [
            LoginController::class  => LoginControllerFactory::class,
            LogoutController::class => LogoutControllerFactory::class,
        ],
    ],
];
