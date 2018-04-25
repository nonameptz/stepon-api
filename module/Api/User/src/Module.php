<?php

namespace Api\User;

use ZF\Apigility\Provider\ApigilityProviderInterface;
use Zend\ModuleManager\Feature;

class Module implements ApigilityProviderInterface, Feature\ConfigProviderInterface
{
    /**
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return array_merge_recursive(
            include __DIR__ . '/../config/login.config.php',
            include __DIR__ . '/../config/registration.config.php',
            include __DIR__ . '/../config/user.config.php',
            include __DIR__ . '/../config/repeat.email.code.config.php'
        );
    }

    public function getAutoloaderConfig()
    {
        return [
            'ZF\Apigility\Autoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__,
                ],
            ],
        ];
    }
}
