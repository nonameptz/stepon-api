<?php

namespace User;

use Application\Admin\Controller\AdminController;
use Application\Admin\Controller\Factory\AdminControllerFactory;

return [
    'router'          => [
        'routes' => [
            'admin' => [
                'type'          => 'Literal',
                'options'       => [
                    'route'    => '/admin',
                    'defaults' => [
                        'controller' => AdminController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
            ],
        ],
    ],
    'controllers'     => [
        'factories' => [
            AdminController::class => AdminControllerFactory::class,
        ],
    ],
];
