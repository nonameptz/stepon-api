<?php

namespace User\Admin\Adapter\Factory;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use User\Admin\Adapter\AdminAuthAdapter;
use Doctrine\ORM\EntityRepository;
use User\Entity\User;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class AdminAuthAdapterFactory
 *
 * @package User\Admin\Adapter\Factory
 */
class AdminAuthAdapterFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return AdminAuthAdapter
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var EntityRepository $repository */
        $repository  = $container->get(EntityManager::class)->getRepository(User::class);
        $authAdapter = new AdminAuthAdapter($repository);

        return $authAdapter;
    }
}