<?php

namespace Application\Util\Criterion\Criteria\Doctrine;

use Doctrine\ORM\QueryBuilder;

/**
 * Class DoctrineOrderCriteria
 *
 * @package Application\Util\Criterion\Criteria\Doctrine
 */
class DoctrineOrderCriteria extends AbstractDoctrineCriteria
{
    const ORDER_ASCENDING  = 'ASC';
    const ORDER_DESCENDING = 'DESC';

    protected $sort;

    /**
     * DoctrineOrderCriteria constructor.
     *
     * @param string $sort
     */
    public function __construct($sort)
    {
        $this->sort = (string)$sort;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string       $rootAlias
     *
     * @return bool TRUE if criteria was applied
     */
    protected function doApply(QueryBuilder $queryBuilder, $rootAlias)
    {
        $order = strtoupper($this->value) === self::ORDER_DESCENDING
            ? self::ORDER_DESCENDING
            : self::ORDER_ASCENDING;

        $queryBuilder->addOrderBy(sprintf('%s.%s', $rootAlias, $this->sort), $order);

        return true;
    }
}
