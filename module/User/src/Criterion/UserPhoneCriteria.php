<?php

namespace User\Criterion;

use Application\Util\Criterion\Criteria\Doctrine\AbstractDoctrineCriteria;
use Doctrine\ORM\QueryBuilder;

/**
 * Class UserPhoneCriteria
 *
 * @package User\Criterion
 */
class UserPhoneCriteria extends AbstractDoctrineCriteria
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

        $queryBuilder->andWhere(sprintf('%s.phone = :phone', $rootAlias))
                     ->setParameter(':phone', $this->value);

        return true;
    }
}