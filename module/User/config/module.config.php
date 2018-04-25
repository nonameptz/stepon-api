<?php

namespace User;

use User\Controller\ThankPage\ThankPageController;
use User\Driver\Factory\UserDriverFactory;
use User\Driver\UserDriver;
use User\Model\Factory\UserModelFactory;
use User\Model\UserModel;

return [
    'doctrine'        => [
        'driver' => [
            'stepon_user_entities' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => [
                    __DIR__ . '/../src/Entity',
                ],
            ],
            'orm_default'            => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => 'stepon_user_entities',
                ],
            ],
        ],
    ],
    'router'          => [
        'routes' => [
            'user' => [
                'type'          => 'Literal',
                'options'       => [
                    'route' => '/user',
                ],
                'may_terminate' => false,
                'child_routes' => [
                    'thank-page' => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => '/thank-page',
                            'defaults' => [
                                'controller' => ThankPageController::class,
                                'action'     => 'thank',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'view_manager'    => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'controllers'     => [
        'invokables' => [
            ThankPageController::class => ThankPageController::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            UserDriver::class     => UserDriverFactory::class,
            UserModel::class      => UserModelFactory::class,
        ],
    ],
];
