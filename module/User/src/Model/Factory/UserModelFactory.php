<?php

namespace User\Model\Factory;

use Application\Driver\AccessTokenDriver;
use Application\Driver\RefreshTokenDriver;
use Interop\Container\ContainerInterface;
use User\Driver\UserDriver;
use User\Model\UserModel;
use Zend\Router\RouteStackInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class UserModelFactory
 *
 * @package User\Model\Factory
 */
class UserModelFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return UserModel
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var UserDriver $driver */
        $driver = $container->get(UserDriver::class);
        /** @var AccessTokenDriver $accessTokenDriver */
        $accessTokenDriver = $container->get(AccessTokenDriver::class);
        /** @var RefreshTokenDriver $refreshTokenDriver */
        $refreshTokenDriver = $container->get(RefreshTokenDriver::class);
        /** @var RouteStackInterface $router */
        $router = $container->get('Router');

        return new UserModel($driver, $accessTokenDriver, $refreshTokenDriver, $router);
    }
}