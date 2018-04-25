<?php

namespace Util;

use Zend\ModuleManager\Feature\ConfigProviderInterface;

/**
 * Class Module
 *
 * @package Util\AppLog
 */
class Module implements ConfigProviderInterface
{

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return array_merge_recursive(
            include __DIR__ . '/../config/logger.config.php'
        );
    }
}
