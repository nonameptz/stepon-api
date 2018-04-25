<?php

namespace User;

use Zend\ModuleManager\Feature\ConfigProviderInterface;

/**
 * Class Module
 *
 * @package User
 */
class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return array_merge_recursive(
            include __DIR__ . '/../config/module.config.php',
            include __DIR__ . '/../config/confirm.email.config.php',
            include __DIR__ . '/../config/admin.config.php'
        );
    }
}