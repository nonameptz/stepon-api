<?php

namespace Api\User\V1\Rpc\Registration\Factory;

use Api\User\V1\Lib\Current\Converter\CurrentUserDtoConverter;
use Api\User\V1\Rpc\Registration\RegistrationController;
use Application\Client\Sms\SmsClient;
use Application\Service\MailService;
use Interop\Container\ContainerInterface;
use User\Model\UserModel;
use Zend\Hydrator\HydratorInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class RegistrationControllerFactory
 *
 * @package Api\User\V1\Rpc\Registration\Factory
 */
class RegistrationControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return RegistrationController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var UserModel $userModel */
        $userModel = $container->get(UserModel::class);
        /** @var MailService $mailService */
        $mailService = $container->get(MailService::class);
        /** @var HydratorInterface $hydrator */
        $hydrator = $container->get('HydratorManager')->get('base.application.hydrator');
        /** @var CurrentUserDtoConverter $converter */
        $converter = $container->get(CurrentUserDtoConverter::class);

        return new RegistrationController($userModel, $mailService, $hydrator, $converter);
    }
}
