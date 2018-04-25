<?php

namespace Api\User\V1\Rpc\Logout\Factory;

use Api\User\V1\Rpc\Logout\LogoutController;
use Interop\Container\ContainerInterface;
use User\Model\UserModel;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class LogoutControllerFactory
 *
 * @package Api\User\V1\Rpc\Factory
 */
class LogoutControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return LogoutController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var UserModel $userModel */
        $userModel = $container->get(UserModel::class);

        return new LogoutController($userModel);
    }
}
