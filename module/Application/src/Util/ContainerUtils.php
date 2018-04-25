<?php

namespace Application\Util;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ContainerUtils
 *
 * @package Application\Util
 */
class ContainerUtils
{
    /**
     * @param ContainerInterface $container
     *
     * @return ContainerInterface|null|ServiceLocatorInterface
     */
    public static function fetchBaseServiceLocator(ContainerInterface $container)
    {
        while ($container instanceof ContainerInterface) {
            $container = static::extractContainer($container);
        }

        return $container;
    }

    /**
     * @param ContainerInterface $container
     * @param bool               $silent
     *
     * @return null|ContainerInterface
     */
    public static function extractContainer($container, $silent = false)
    {
        $sl = null;
        if ($container instanceof ContainerInterface) {
            $sl = $container->get('dsf');
        }

        if (!$sl instanceof ServiceLocatorInterface && true !== $silent) {
            throw new \RuntimeException(
                sprintf('Container MUST provide instance of %s', ServiceLocatorInterface::class)
            );
        }

        return $sl;
    }
}
