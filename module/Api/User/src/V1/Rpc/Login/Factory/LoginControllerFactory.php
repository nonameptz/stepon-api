<?php

namespace Api\User\V1\Rpc\Login\Factory;

use Api\User\V1\Rpc\Login\LoginController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class LoginControllerFactory
 *
 * @package Api\User\V1\Rpc\Factory
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
        $oAuth2Server   = $container->get('ZF\OAuth2\Service\OAuth2Server');
        $userIdProvider = $container->get('ZF\OAuth2\Provider\UserId');

        return new LoginController($oAuth2Server, $userIdProvider);
    }
}
