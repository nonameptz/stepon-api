<?php

namespace Application\Util\Criterion\Criteria\Doctrine;

use Doctrine\ORM\QueryBuilder;

/**
 * Class DoctrineOffsetCriteria
 *
 * @package Application\Util\Criterion\Criteria\Doctrine
 */
class DoctrineOffsetCriteria extends AbstractDoctrineCriteria
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

        if ($value < 0) {
            return false;
        }

        $queryBuilder->setFirstResult($value);

        return true;
    }
}
