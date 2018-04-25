<?php

namespace Application\Driver\Factory;

use Application\Driver\RefreshTokenDriver;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class RefreshTokenDriverFactory
 *
 * @package Application\Driver\Factory
 */
class RefreshTokenDriverFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return RefreshTokenDriver
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);

        return new RefreshTokenDriver($entityManager);
    }
}