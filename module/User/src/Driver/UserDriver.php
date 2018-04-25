<?php

namespace User\Driver;

use Application\Util\Criterion\Criteria\CriteriaInterface;
use Application\Util\Criterion\Filter;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use User\Entity\User;

/**
 * Class UserDriver
 *
 * @package User\Driver
 */
class UserDriver
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * Driver constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param $id
     *
     * @return null|User
     */
    public function find($id)
    {
        $repository = $this->em->getRepository(User::class);
        /** @var User $user */
        $user = $repository->find($id);

        return $user;
    }

    /**
     * @param array      $criteria
     * @param array|null $orderBy
     *
     * @return User
     */
    public function findOneBy(array $criteria, array $orderBy = null)
    {
        $repository = $this->em->getRepository(User::class);
        /** @var User $user */
        $user = $repository->findOneBy($criteria, $orderBy);

        return $user;
    }

    /**
     * @param Filter $filter
     *
     * @return User[]
     */
    public function findByFilter(Filter $filter)
    {
        $qb = $this->getQueryBuilder();
        /** @var CriteriaInterface $criteria */
        foreach ($filter as $criteria) {
            $criteria->apply($qb);
        }
        $qb->distinct();

        return $qb->getQuery()->getResult();
    }

    /**
     * @param Filter $filter
     *
     * @return int
     */
    public function countByFilter(Filter $filter)
    {
        $qb = $this->getQueryBuilder();
        /** @var CriteriaInterface $criteria */
        foreach ($filter as $criteria) {
            $criteria->apply($qb);
        }
        $qb->select('count(DISTINCT user.id)')->distinct();

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param User $user
     *
     * @return User
     */
    public function save(User $user)
    {
        $this->em->persist($user);
        $this->em->flush($user);

        return $user;
    }

    public function delete($user)
    {
        $this->em->remove($user);
        $this->em->flush();
    }

    /**
     * @return QueryBuilder
     */
    private function getQueryBuilder()
    {
        /** @var EntityRepository $repository */
        $repository = $this->em->getRepository(User::class);
        $qb         = $repository->createQueryBuilder('user');

        return $qb;
    }
}