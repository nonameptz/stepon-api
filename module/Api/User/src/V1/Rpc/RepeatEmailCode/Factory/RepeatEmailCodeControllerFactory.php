<?php

namespace Api\User\V1\Rpc\RepeatEmailCode\Factory;

use Api\User\V1\Rpc\RepeatEmailCode\RepeatEmailCodeController;
use Application\Service\MailService;
use Interop\Container\ContainerInterface;
use User\Model\UserModel;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ConfirmEmailControllerFactory
 *
 * @package Api\User\V1\Rpc\ConfirmEmail\Factory
 */
class RepeatEmailCodeControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return RepeatEmailCodeController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var UserModel $userModel */
        $userModel = $container->get(UserModel::class);
        /** @var MailService $mailService */
        $mailService = $container->get(MailService::class);

        return new RepeatEmailCodeController($userModel, $mailService);
    }
}
