<?php

namespace Application\Driver;

use Application\Entity\RefreshToken;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use User\Entity\User;

/**
 * Class RefreshTokenDriver
 *
 * @package Application\Driver
 */
class RefreshTokenDriver
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * RefreshTokenDriver constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $refreshToken
     *
     * @return RefreshToken
     */
    public function find(string $refreshToken)
    {
        $repository = $this->entityManager->getRepository(RefreshToken::class);
        /** @var RefreshToken $refreshToken */
        $refreshToken = $repository->find($refreshToken);

        return $refreshToken;
    }

    /**
     * @param User $user
     */
    public function clear(User $user)
    {
        /** @var EntityRepository $repository */
        $repository = $this->entityManager->getRepository(RefreshToken::class);
        $qb         = $repository->createQueryBuilder('rt');
        $qb->delete()
           ->where('rt.username = :username')
           ->setParameter('username', $user->getUsername());

        $qb->getQuery()->execute();
    }
}