<?php

namespace Util\Logger\Factory;

use Util\Logger\Handler\HandlerInterface;
use Util\Logger\Helper\HelperInterface;
use Util\Logger\Helper\Request;
use Util\Logger\Helper\Response;
use Util\Logger\MessageLogger;

/**
 * Class LoggerFactory
 *
 * @package Util\Logger\Factory
 */
class LoggerFactory
{
    const CONFIG_RELATIVE_PATH = 'config/logger.config.php';
    const MODULE_PATH_SHIFT    = '/../../../';

    const PRODUCTION_ENV = 'prod';
    const DEMO_ENV       = 'branch';

    /**
     * @var array
     */
    private static $config;

    /**
     * @var string
     */
    private static $modulePath;

    /**
     * Init of MessageLogger
     */
    public static function init()
    {
        self::$modulePath = __DIR__ . self::MODULE_PATH_SHIFT;

        if (!is_array(self::$config)) {
            self::$config = self::loadConfig();
        }

        $messageLogger = MessageLogger::getInstance();
        self::initHandlers($messageLogger);
        self::initHelpers($messageLogger);
    }

    /**
     * @return array
     */
    private static function loadConfig()
    {
        $config = include_once self::$modulePath . self::CONFIG_RELATIVE_PATH;
        return is_array($config) ? $config : [];
    }

    /**
     * @param MessageLogger $messageLogger
     */
    private static function initHandlers(MessageLogger $messageLogger)
    {
        if (!is_array(self::$config['handlers'])) {
            return;
        }

        foreach (self::$config['handlers'] as $handlerConfig) {
            if (!isset($handlerConfig['handler']) || !class_exists($handlerConfig['handler'])) {
                continue;
            }

            $handler = new $handlerConfig['handler']();
            if (!$handler instanceof HandlerInterface) {
                continue;
            }

            if (isset($handlerConfig['config'])) {
                try {
                    $handler->init($handlerConfig['config']);
                } catch (\Exception $exception) {
                    continue;
                }
            }

            $messageLogger->pushHandler($handler);
        }
    }

    /**
     * @param MessageLogger $messageLogger
     */
    private static function initHelpers(MessageLogger $messageLogger)
    {
        $helpers = [
            Request::class,
            Response::class,
        ];

        foreach ($helpers as $helperClass) {
            if (!class_exists($helperClass)) {
                continue;
            }

            try {
                $helper = new $helperClass();
            } catch (\Exception $exception) {
                continue;
            }

            if (!$helper instanceof HelperInterface) {
                continue;
            }

            $messageLogger->pushHelper($helper);
        }
    }
}
