<?php

namespace Api\User;

use Api\User\V1\Lib\Registration\InputFilter\RegistrationInputFilter;
use Api\User\V1\Rpc\Registration\Factory\RegistrationControllerFactory;
use Api\User\V1\Rpc\Registration\RegistrationController;

return [
    'router'                 => [
        'routes' => [
            'api.user' => [
                'child_routes' => [
                    'registration' => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => '/registration',
                            'defaults' => [
                                'controller' => RegistrationController::class,
                                'action'     => 'registration',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'zf-rpc'                 => [
        RegistrationController::class => [
            'service_name' => 'registration',
            'http_methods' => [
                0 => 'POST',
            ],
            'route_name'   => 'api.user/registration',
        ],
    ],
    'zf-content-negotiation' => [
        'controllers'            => [
            RegistrationController::class => 'HalJson',
        ],
        'accept_whitelist'       => [
            RegistrationController::class => [
                0 => 'application/vnd.api-registration.user.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
        ],
        'content_type_whitelist' => [
            RegistrationController::class => [
                0 => 'application/vnd.api-registration.user.v1+json',
                1 => 'application/json',
            ],
        ],
    ],
    'zf-mvc-auth'            => [
        'authorization' => [
            RegistrationController::class => [
                'actions' => [
                    'registration' => [
                        'POST' => false,
                    ],
                ],
            ],
        ],
    ],
    'zf-content-validation'  => [
        RegistrationController::class => [
            'input_filter' => RegistrationInputFilter::class,
        ],
    ],
    'controllers'            => [
        'factories' => [
            RegistrationController::class => RegistrationControllerFactory::class,
        ],
    ],
    'input_filters'          => [
        'invokables' => [
            RegistrationInputFilter::class => RegistrationInputFilter::class,
        ],
    ],
];
