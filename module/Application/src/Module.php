<?php

namespace Application;

use Application\Log\Listener\ApplicationErrorListener;
use Doctrine\DBAL\Types\Type;
use Order\Enum\OrderStatus;
use User\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use User\Enum\CodeType;
use User\Enum\UserRole;
use User\Enum\UserStatus;
use User\Enum\UserType;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature;
use ZF\MvcAuth\Authorization\AclAuthorization;
use ZF\MvcAuth\Identity\AuthenticatedIdentity;
use ZF\MvcAuth\MvcAuthEvent;

class Module implements Feature\ConfigProviderInterface
{
    public function getConfig()
    {
        return array_merge_recursive(
            include __DIR__ . '/../config/module.config.php',
            include __DIR__ . '/../config/admin.config.php'
        );
    }

    /**
     * {@inheritDoc}
     */
    public function onBootstrap(EventInterface $event)
    {
        /* @var \Zend\Mvc\Application $application */
        $application    = $event->getTarget();
        $serviceManager = $application->getServiceManager();
        $eventManager   = $application->getEventManager();

        /** TODO: think about this */
        $eventManager->attach(
            MvcAuthEvent::EVENT_AUTHORIZATION,
            function (MvcAuthEvent $mvcAuthEvent) use ($serviceManager) {
                $identity = $mvcAuthEvent->getIdentity();
                if (empty($identity) || empty($identity->getAuthenticationIdentity())) {
                    return;
                }
                $authenticationIdentity = $identity->getAuthenticationIdentity();
                if (empty($authenticationIdentity['user_id'])) {
                    return;
                }
                /** @var EntityRepository $userRepository */
                $userRepository = $serviceManager->get(EntityManager::class)->getRepository(User::class);
                /** @var User $user */
                $user = $userRepository->findOneBy(['username' => $authenticationIdentity['user_id']]);
                if (empty($user) || $user->getStatus() != UserStatus::ACTIVE) {
                    /** @var AclAuthorization $authorization */
                    $authorization = $mvcAuthEvent->getAuthorizationService();
                    $authorization->deny(null);
                    return;
                }
                $mvcAuthEvent->getMvcEvent()->setParam('ZF\MvcAuth\Identity', new AuthenticatedIdentity($user));
            },
            100
        );

        /** @var ApplicationErrorListener $errorListener */
        $errorListener = $serviceManager->get(ApplicationErrorListener::class);
        $errorListener->attach($eventManager);

        Type::addType('enumUserRole', UserRole::class);
        Type::addType('enumUserStatus', UserStatus::class);
        Type::addType('enumCodeType', CodeType::class);
    }
}
