<?php

namespace User\Criterion;

use Application\Util\Criterion\Criteria\Doctrine\AbstractDoctrineCriteria;
use Doctrine\ORM\QueryBuilder;

/**
 * Class UserEmailCriteria
 *
 * @package User\Criterion
 */
class UserEmailCriteria extends AbstractDoctrineCriteria
{
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

        $queryBuilder->andWhere(sprintf('%s.email = :email', $rootAlias))
                     ->setParameter(':email', $this->value);

        return true;
    }
}