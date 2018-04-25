<?php

namespace User\Admin\Service;

use Doctrine\ORM\EntityRepository;
use User\Entity\User;
use User\Enum\UserRole;
use Zend\Authentication\AuthenticationService as ZFAuthenticationService;

/**
 * Class AdminAuthenticationService
 *
 * @package User\Admin\Service
 */
class AdminAuthenticationService extends ZFAuthenticationService
{
    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * AuthenticationService constructor.
     *
     * @param EntityRepository $repository
     */
    public function __construct(EntityRepository $repository)
    {
        parent::__construct();
        $this->repository = $repository;
    }

    /**
     * @return null|User
     */
    public function getIdentity()
    {
        $identity = parent::getIdentity();
        if (empty($identity)) {
            return null;
        }
        /** @var User $user */
        $user = $this->repository->find($identity);
        if ($user->getRole() != UserRole::ADMIN) {
            return null;
        }

        return $user;
    }
}
