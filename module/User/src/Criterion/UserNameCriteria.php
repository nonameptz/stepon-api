<?php

namespace User\Criterion;

use Application\Util\Criterion\Criteria\Doctrine\AbstractDoctrineCriteria;
use Application\Util\Criterion\Criteria\Doctrine\JoinTableTrait;
use Doctrine\ORM\QueryBuilder;

/**
 * Class UserCodeCriteria
 *
 * @package User\Criterion
 */
class UserNameCriteria extends AbstractDoctrineCriteria
{
    use JoinTableTrait;

    /**
     * @param QueryBuilder $queryBuilder
     * @param string       $rootAlias
     *
     * @return bool TRUE if criteria was applied
     */
    protected function doApply(QueryBuilder $queryBuilder, $rootAlias)
    {
        if (empty($this->value)) {
            return false;
        }

        $queryBuilder->andWhere(sprintf('%s.username = :username', $rootAlias))
                     ->setParameter(':username', $this->value);

        return true;
    }
}