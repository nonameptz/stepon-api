<?php

namespace Util;

use Util\Logger\Handler\DebugHandler;
use Util\Logger\Handler\ErrorHandler;
use Util\Logger\Handler\FatalErrorHandler;
use Util\Logger\Helper\Request;
use Util\Logger\Helper\Response;
use Util\Logger\Message\DebugMessage;
use Util\Logger\Message\ErrorMessage;
use Util\Logger\Message\EventMessage;
use Util\Logger\Message\ExceptionMessage;
use Util\Logger\Message\ExternalServiceMessage;
use Util\Logger\Message\RequestMessage;
use Util\Logger\Message\RumErrorMessage;

$projectRoot = getcwd();
return [
    'handlers' => [
        'fatalError' => [
            'handler' => FatalErrorHandler::class,
            'config'  => [
                'path'        => $projectRoot . '/data/logs/stepon_app_log/',
                'namePattern' => 'fatal_error_%s.json',
                'chmod'       => 0666,
                'limit'       => 3,
                'processable' => [
                    ErrorMessage::class,
                ],
                'ignored'     => [
                    [
                        'level' => E_USER_DEPRECATED,
                        'file'  => 'vendor/twig/twig/lib/Twig/Loader/Filesystem.php',
                    ],
                    [
                        'level' => E_USER_DEPRECATED,
                        'file'  => 'vendor/twig/twig/lib/Twig/Environment.php',
                    ],
                    [
                        'level' => E_USER_DEPRECATED,
                        'file'  => 'vendor/twig/twig/lib/Twig/Function.php',
                    ],
                ],
            ],
        ],
        'error'      => [
            'handler' => ErrorHandler::class,
            'config'  => [
                'path'        => $projectRoot . '/data/logs/stepon_app_log/',
                'namePattern' => 'error_%s.json',
                'index'       => 'error',
                'chmod'       => 0666,
                'limit'       => 3,
                'processable' => [
                    ErrorMessage::class,
                    ExceptionMessage::class,
                ],
                'helpers'     => [
                    Request::class,
                    Response::class,
                ],
                'ignored'     => [
                    [
                        'level' => E_USER_DEPRECATED,
                        'file'  => 'vendor/twig/twig/lib/Twig/Loader/Filesystem.php',
                    ],
                    [
                        'level' => E_USER_DEPRECATED,
                        'file'  => 'vendor/twig/twig/lib/Twig/Environment.php',
                    ],
                    [
                        'level' => E_USER_DEPRECATED,
                        'file'  => 'vendor/twig/twig/lib/Twig/Function.php',
                    ],
                ],
            ],
        ],
        'debug'      => [
            'handler' => DebugHandler::class,
            'config'  => [
                'path'        => $projectRoot . '/data/logs/stepon_app_log/',
                'namePattern' => 'application_%s.json',
                'index'       => 'error',
                'chmod'       => 0666,
                'limit'       => 3,
                'processable' => [
                    DebugMessage::class,
                    EventMessage::class,
                    ExternalServiceMessage::class,
                    RumErrorMessage::class,
                ],
                'helpers'     => [
                    Request::class,
                    Response::class,
                ],
            ],
        ],
        'request'    => [
            'handler' => DebugHandler::class,
            'config'  => [
                'path'        => $projectRoot . '/data/logs/stepon_app_log/',
                'namePattern' => 'application_%s.json',
                'chmod'       => 0666,
                'limit'       => 3,
                'processable' => [
                    RequestMessage::class,
                ],
                'helpers'     => [
                    Request::class,
                    Response::class,
                ],
            ],
        ],
    ],
];