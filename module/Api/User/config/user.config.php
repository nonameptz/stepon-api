<?php

namespace Api\User;

use Api\User\V1\Lib\Current\Converter\CurrentUserDtoConverter;
use Api\User\V1\Lib\Current\Dto\CurrentUserDto;
use Api\User\V1\Lib\Current\InputFilter\CurrentUserInputFilter;
use Api\User\V1\Rest\User\Factory\UserResourceFactory;
use Api\User\V1\Rest\User\UserController;
use Api\User\V1\Rest\User\UserResource;
use User\Entity\User;
use Zend\Hydrator\ClassMethods;

return [
    'router'                 => [
        'routes' => [
            'api.user' => [
                'type'          => 'Literal',
                'options'       => [
                    'route' => '/api/user',
                ],
                'may_terminate' => false,
                'child_routes'  => [
                    'current' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/current[/:user_id]',
                            'defaults'    => [
                                'controller' => UserController::class,
                            ],
                            'constraints' => [
                                'user_id' => '[0-9]+',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'zf-rest'                => [
        UserController::class => [
            'listener'                => UserResource::class,
            'route_name'              => 'api.user/current',
            'route_identifier_name'   => 'user_id',
            'entity_http_methods'     => [
                'PUT',
            ],
            'collection_http_methods' => [
                'GET',
            ],
            'entity_class'            => User::class,
            'service_name'            => 'user',
        ],
    ],
    'zf-content-negotiation' => [
        'controllers'            => [
            UserController::class => 'HalJson',
        ],
        'accept_whitelist'       => [
            UserController::class => [
                0 => 'application/vnd.api.user.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
        ],
        'content_type_whitelist' => [
            UserController::class => [
                0 => 'application/vnd.api.user.v1+json',
                1 => 'application/json',
            ],
        ],
    ],
    'zf-mvc-auth'            => [
        'authorization' => [
            UserController::class => [
                'entity'     => [
                    'PUT' => true,
                ],
                'collection' => [
                    'GET' => true,
                ],
            ],
        ],
    ],
    'zf-hal'                 => [
        'metadata_map' => [
            CurrentUserDto::class => [
                'entity_identifier_name' => 'id',
                'route_name'             => 'api.user/current',
                'hydrator'               => ClassMethods::class,
            ],
        ],
    ],
    'zf-content-validation'  => [
        UserController::class => [
            'input_filter' => CurrentUserInputFilter::class,
        ],
    ],
    'input_filters'          => [
        'invokables' => [
            CurrentUserInputFilter::class => CurrentUserInputFilter::class,
        ],
    ],
    'service_manager'        => [
        'invokables' => [
            CurrentUserDtoConverter::class => CurrentUserDtoConverter::class,
        ],
        'factories'  => [
            UserResource::class => UserResourceFactory::class,
        ],
    ],
];
