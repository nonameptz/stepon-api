<?php

namespace User\Driver\Factory;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use User\Driver\UserDriver;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class UserDriverFactory
 *
 * @package User\Driver\Factory
 */
class UserDriverFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return UserDriver
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);

        return new UserDriver($entityManager);
    }
}