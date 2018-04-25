<?php

namespace User;

use User\Controller\ConfirmEmail\ConfirmEmailController;
use User\Controller\ConfirmEmail\Factory\ConfirmEmailControllerFactory;

return [
    'router'      => [
        'routes' => [
            'user' => [
                'child_routes' => [
                    'confirm-email' => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => '/confirm-email',
                            'defaults' => [
                                'controller' => ConfirmEmailController::class,
                                'action'     => 'confirm',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            ConfirmEmailController::class => ConfirmEmailControllerFactory::class,
        ],
    ],
];
