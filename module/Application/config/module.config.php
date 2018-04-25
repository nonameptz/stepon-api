<?php

namespace Application;

use Application\Adapter\Factory\PdoAdapterFactory;
use Application\Controller\IndexController;
use Application\Controller\SorryPageController;
use Application\Driver\AccessTokenDriver;
use Application\Driver\Factory\AccessTokenDriverFactory;
use Application\Driver\Factory\RefreshTokenDriverFactory;
use Application\Driver\RefreshTokenDriver;
use Application\Filter\Number;
use Application\Hydrator\Factory\BaseHydratorFactory;
use Application\Log\Listener\ApplicationErrorListener;
use Application\PluginManager\Factory\HydratorPluginManagerFactory;
use Application\Service\Factory\MailServiceFactory;
use Application\Service\MailService;
use Application\Storage\Factory\MemcachedPoolFactory;
use Application\Validator\Factory\ObjectExistenceValidatorAbstractFactory;
use Application\Validator\PasswordValidator;
use Application\Validator\PhoneValidator;
use Zend\Hydrator\HydratorPluginManager;

return [
    'doctrine'        => [
        'driver' => [
            'stepon_application_entities' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => [
                    __DIR__ . '/../src/Entity',
                ],
            ],
            'orm_default'                   => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => 'stepon_application_entities',
                ],
            ],
        ],
    ],
    'router'          => [
        'routes' => [
            'home'       => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'sorry-page' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/sorry-page',
                    'defaults' => [
                        'controller' => SorryPageController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers'     => [
        'invokables' => [
            IndexController::class     => IndexController::class,
            SorryPageController::class => SorryPageController::class,
        ],
    ],
    'view_manager'    => [
        'display_not_found_reason' => false,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map'             => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack'      => [
            __DIR__ . '/../view',
        ],
    ],
    'service_manager' => [
        'aliases'            => [
            'HydratorManager' => HydratorPluginManager::class,
        ],
        'invokables'         => [
            ApplicationErrorListener::class => ApplicationErrorListener::class,
        ],
        'factories'          => [
            'memcached'                    => MemcachedPoolFactory::class,
            'ZF\OAuth2\Adapter\PdoAdapter' => PdoAdapterFactory::class,
            HydratorPluginManager::class   => HydratorPluginManagerFactory::class,
            AccessTokenDriver::class       => AccessTokenDriverFactory::class,
            RefreshTokenDriver::class      => RefreshTokenDriverFactory::class,
            MailService::class             => MailServiceFactory::class,
        ],
        'abstract_factories' => [
            ObjectExistenceValidatorAbstractFactory::class,
        ],
    ],
    'validators'      => [
        'invokables'         => [
            PasswordValidator::class => PasswordValidator::class,
            PhoneValidator::class    => PhoneValidator::class,
        ],
        'abstract_factories' => [
            ObjectExistenceValidatorAbstractFactory::class,
        ],
    ],
    'hydrators'       => [
        'factories' => [
            'base.application.hydrator' => BaseHydratorFactory::class,
        ],
    ],
    'filters'         => [
        'invokables' => [
            'number' => Number::class,
        ],
    ],
];
