<?php

namespace Api\User;

use Api\User\V1\Lib\RepeatEmailCode\InputFilter\RepeatEmailCodeInputFilter;
use Api\User\V1\Rpc\RepeatEmailCode\Factory\RepeatEmailCodeControllerFactory;
use Api\User\V1\Rpc\RepeatEmailCode\RepeatEmailCodeController;

return [
    'router'                 => [
        'routes' => [
            'api.user' => [
                'child_routes' => [
                    'repeat-email-code' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/repeat/email/code',
                            'defaults' => [
                                'controller' => RepeatEmailCodeController::class,
                                'action'     => 'repeat-code',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'zf-rpc'                 => [
        RepeatEmailCodeController::class => [
            'service_name' => 'repeat-email-code',
            'http_methods' => [
                0 => 'POST',
            ],
            'route_name'   => 'api.user/repeat-email-code',
        ],
    ],
    'zf-content-negotiation' => [
        'controllers'            => [
            RepeatEmailCodeController::class => 'HalJson',
        ],
        'accept_whitelist'       => [
            RepeatEmailCodeController::class => [
                0 => 'application/vnd.api-repeat-email-code.user.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
        ],
        'content_type_whitelist' => [
            RepeatEmailCodeController::class => [
                0 => 'application/vnd.api-repeat-email-code.user.v1+json',
                1 => 'application/json',
            ],
        ],
    ],
    'zf-content-validation'  => [
        RepeatEmailCodeController::class => [
            'input_filter' => RepeatEmailCodeInputFilter::class,
        ],
    ],
    'zf-mvc-auth'            => [
        'authorization' => [
            RepeatEmailCodeController::class => [
                'actions' => [
                    'repeat-code' => [
                        'POST' => false,
                    ],
                ],
            ],
        ],
    ],
    'controllers'            => [
        'factories' => [
            RepeatEmailCodeController::class => RepeatEmailCodeControllerFactory::class,
        ],
    ],
    'input_filters'          => [
        'invokables' => [
            RepeatEmailCodeInputFilter::class => RepeatEmailCodeInputFilter::class,
        ],
    ],
];
