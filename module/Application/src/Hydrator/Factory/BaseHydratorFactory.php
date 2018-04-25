<?php

namespace Application\Hydrator\Factory;

use Interop\Container\ContainerInterface;
use Zend\Hydrator\ClassMethods;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class BaseHydratorFactory
 *
 * @package Application\Hydrator\Factory
 */
class BaseHydratorFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ClassMethods
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ClassMethods(false);
    }
}
