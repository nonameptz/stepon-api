<?php

namespace Api\User\V1\Rest\User\Factory;

use Api\User\V1\Lib\Current\Converter\CurrentUserDtoConverter;
use Api\User\V1\Rest\User\UserResource;
use Interop\Container\ContainerInterface;
use User\Model\UserModel;
use Zend\Hydrator\HydratorInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class UserResourceFactory
 *
 * @package Api\User\V1\Rest\User\Factory
 */
class UserResourceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return UserResource
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var UserModel $model */
        $model = $container->get(UserModel::class);
        /** @var CurrentUserDtoConverter $converter */
        $converter = $container->get(CurrentUserDtoConverter::class);
        /** @var HydratorInterface $hydrator */
        $hydrator = $container->get('HydratorManager')->get('base.application.hydrator');

        return new UserResource($model, $converter, $hydrator);
    }
}
