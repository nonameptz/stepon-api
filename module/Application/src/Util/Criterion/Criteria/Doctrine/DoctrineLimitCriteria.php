<?php

namespace Application\Util\Criterion\Criteria\Doctrine;

use Doctrine\ORM\QueryBuilder;

/**
 * Class DoctrineLimitCriteria
 *
 * @package Util\Criterion\Criteria\Doctrine
 * @author  Eduard Posinitskii <eduard.posinitskii@veeam.com>
 */
class DoctrineLimitCriteria extends AbstractDoctrineCriteria
{

    /**
     * @param QueryBuilder $queryBuilder
     * @param string       $rootAlias
     *
     * @return bool TRUE if criteria was applied
     */
    protected function doApply(QueryBuilder $queryBuilder, $rootAlias)
    {
        $value = intval($this->value);

        if (empty($value)) {
            return false;
        }

        $queryBuilder->setMaxResults($value);

        return true;
    }
}
