<?php

namespace Application\Util;

use User\Entity\User;
use Zend\Mvc\MvcEvent;
use ZF\MvcAuth\Identity\AuthenticatedIdentity;

/**
 * Trait UserIdentityTrait
 *
 * @package Application\Util
 */
trait UserIdentityTrait
{
    /**
     * @param mixed $identity
     *
     * @return null|User
     */
    public function extractUser($identity)
    {
        if ($this->isUser($identity) === true) {
            return $identity->getAuthenticationIdentity();
        }

        return null;
    }

    /**
     * @param MvcEvent $e
     *
     * @return User|null
     */
    public function extractUserFromEvent(MvcEvent $e)
    {
        $identity = $e->getParam('ZF\MvcAuth\Identity');
        if (empty($identity)) {
            return null;
        }

        return $this->extractUser($identity);
    }

    /**
     * @param mixed $identity
     *
     * @return bool
     */
    public function isUser($identity)
    {
        return $identity instanceof AuthenticatedIdentity && $identity->getAuthenticationIdentity() instanceof User;
    }
}
