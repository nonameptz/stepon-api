<?php

namespace Application\Driver;

use Application\Entity\AccessToken;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use User\Entity\User;

/**
 * Class AccessTokenDriver
 *
 * @package Application\Driver
 */
class AccessTokenDriver
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * AccessTokenDriver constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $accessToken
     *
     * @return AccessToken
     */
    public function find(string $accessToken)
    {
        $repository = $this->entityManager->getRepository(AccessToken::class);
        /** @var AccessToken $accessToken */
        $accessToken = $repository->find($accessToken);

        return $accessToken;
    }

    /**
     * @param User $user
     */
    public function clear(User $user)
    {
        /** @var EntityRepository $repository */
        $repository = $this->entityManager->getRepository(AccessToken::class);
        $qb         = $repository->createQueryBuilder('at');
        $qb->delete()
           ->where('at.username = :username')
           ->setParameter('username', $user->getUsername());

        $qb->getQuery()->execute();
    }
}