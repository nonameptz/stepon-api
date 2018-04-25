<?php

namespace Application\Driver\Factory;

use Application\Driver\AccessTokenDriver;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class AccessTokenDriverFactory
 *
 * @package Application\Driver\Factory
 */
class AccessTokenDriverFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return AccessTokenDriver
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);

        return new AccessTokenDriver($entityManager);
    }
}