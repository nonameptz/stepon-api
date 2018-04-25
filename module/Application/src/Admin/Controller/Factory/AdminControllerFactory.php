<?php

namespace Application\Admin\Controller\Factory;

use Application\Admin\Controller\AdminController;
use Interop\Container\ContainerInterface;
use User\Admin\Service\AdminAuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class AdminControllerFactory
 *
 * @package Application\Admin\Controller\Factory
 */
class AdminControllerFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return AdminController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var  AdminAuthenticationService $authService */
        $authService = $container->get('User\Service\AdminAuthService');

        return new AdminController($authService);
    }
}
