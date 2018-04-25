<?php

namespace Application\PluginManager\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Config;
use Zend\Hydrator\HydratorPluginManagerFactory as BaseFactory;

/**
 * Class HydratorPluginManagerFactory
 *
 * @package Application\PluginManager\Factory
 */
class HydratorPluginManagerFactory extends BaseFactory
{
    /**
     * @param ContainerInterface $container
     * @param string             $name
     * @param array|null         $options
     *
     * @return \Zend\Hydrator\HydratorPluginManager
     */
    public function __invoke(ContainerInterface $container, $name, array $options = null)
    {
        $pluginManager = parent::__invoke($container, $name, $options);

        //TODO: fix problem with hydrators in apigility
        // If we do not have a config service, nothing more to do
        if (!$container->has('config')) {
            return $pluginManager;
        }

        $config = $container->get('config');

        // If we do not have hydrators configuration, nothing more to do
        if (!isset($config['hydrators']) || !is_array($config['hydrators'])) {
            return $pluginManager;
        }

        // Wire service configuration for hydrators
        (new Config($config['hydrators']))->configureServiceManager($pluginManager);

        return $pluginManager;
    }
}