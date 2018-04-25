<?php

namespace User\Controller\ConfirmEmail\Factory;

use Application\Service\MailService;
use Interop\Container\ContainerInterface;
use User\Controller\ConfirmEmail\ConfirmEmailController;
use User\Model\UserModel;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class ConfirmEmailControllerFactory
 *
 * @package User\Controller\ConfirmEmail\Factory
 */
class ConfirmEmailControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return ConfirmEmailController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var UserModel $userModel */
        $userModel = $container->get(UserModel::class);
        /** @var MailService $mailService */
        $mailService = $container->get(MailService::class);

        return new ConfirmEmailController($userModel, $mailService);
    }
}
