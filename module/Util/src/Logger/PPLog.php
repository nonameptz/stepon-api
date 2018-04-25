<?php

namespace Util\Logger;

use Util\Logger\Message\DebugMessage;
use Util\Logger\Message\ErrorMessage;
use Util\Logger\Message\ExceptionMessage;

/**
 * Class PPLog
 *
 * @package Util\Logger
 */
class PPLog
{
    /**
     * @param \Throwable $exception
     * @param array      $context
     */
    public static function exception(\Throwable $exception, array $context = [])
    {
        $container = new ExceptionMessage($exception);
        $container->addContext($context);
        MessageLogger::getInstance()->pushMessage($container);
    }

    /**
     * @param \Throwable $exception
     */
    public static function fatal(\Throwable $exception)
    {
        $container = new ErrorMessage($exception);
        MessageLogger::getInstance()->pushMessage($container);
    }

    /**
     * @param string $message
     * @param array  $context
     */
    public static function debug($message, array $context = [])
    {
        $container = new DebugMessage($message);
        $container->addContext($context);
        MessageLogger::getInstance()->pushMessage($container);
    }
}
