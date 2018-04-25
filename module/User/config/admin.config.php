<?php

namespace User;

use User\Admin\Adapter\AdminAuthAdapter;
use User\Admin\Adapter\Factory\AdminAuthAdapterFactory;
use User\Admin\Controller\Factory\LoginControllerFactory;
use User\Admin\Controller\LoginController;
use User\Admin\Form\AuthForm;
use User\Admin\Form\Factory\AuthFormFactory;
use User\Admin\InputFilter\AuthFormInputFilter;
use User\Admin\Service\Factory\AdminAuthenticationServiceFactory;

return [
    'router'          => [
        'routes' => [
            'admin' => [
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
                                'controller' => LoginController::class,
                                'action'     => 'logout',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'controllers'     => [
        'factories' => [
            LoginController::class => LoginControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            AuthForm::class                 => AuthFormFactory::class,
            AdminAuthAdapter::class         => AdminAuthAdapterFactory::class,
            'User\Service\AdminAuthService' => AdminAuthenticationServiceFactory::class,
        ],
    ],
    'input_filters'   => [
        'invokables' => [
            AuthFormInputFilter::class => AuthFormInputFilter::class,
        ],
    ],
];
