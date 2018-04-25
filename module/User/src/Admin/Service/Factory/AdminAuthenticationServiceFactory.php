<?php

namespace User\Admin\Service\Factory;

use User\Admin\Adapter\AdminAuthAdapter;
use User\Admin\Service\AdminAuthenticationService;
use User\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class AdminAuthenticationServiceFactory
 *
 * @package User\Admin\Service\Factory
 */
class AdminAuthenticationServiceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return AdminAuthenticationService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var AdminAuthAdapter $adapter */
        $adapter = $container->get(AdminAuthAdapter::class);
        /** @var EntityManager $em */
        $em = $container->get(EntityManager::class);
        /** @var EntityRepository $repository */
        $repository = $em->getRepository(User::class);
        $service    = new AdminAuthenticationService($repository);
        $service->setAdapter($adapter);

        return $service;
    }
}