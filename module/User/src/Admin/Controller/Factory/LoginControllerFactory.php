<?php

namespace User\Admin\Controller\Factory;

use Interop\Container\ContainerInterface;
use User\Admin\Controller\LoginController;
use User\Admin\Form\AuthForm;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class LoginControllerFactory
 *
 * @package User\Admin\Controller\Factory
 */
class LoginControllerFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return LoginController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var  AuthenticationService $authService */
        $authService = $container->get('User\Service\AdminAuthService');
        /** @var AuthForm $form */
        $form = $container->get(AuthForm::class);

        return new LoginController($authService, $form);
    }
}
