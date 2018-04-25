<?php

namespace Application\Util\Criterion\Criteria\Doctrine;

use Doctrine\ORM\QueryBuilder;

/**
 * Class DoctrineMultipleOrderCriteria
 *
 * @package Application\Util\Criterion\Criteria\Doctrine
 */
class DoctrineMultipleOrderCriteria extends AbstractDoctrineCriteria
{
    /**
     * @var array
     */
    protected $availableFields;

    /**
     * DoctrineMultipleOrderCriteria constructor.
     *
     * @param array $availableFields
     */
    public function __construct(array $availableFields)
    {
        $this->availableFields = $availableFields;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string       $rootAlias
     *
     * @return bool TRUE if criteria was applied
     */
    protected function doApply(QueryBuilder $queryBuilder, $rootAlias)
    {
        if (!is_array($this->value)) {
            return false;
        }

        foreach ($this->value as $field => $order) {
            if(!in_array($field, $this->availableFields)){
                continue;
            }

            $order = strtoupper($order) === DoctrineOrderCriteria::ORDER_DESCENDING
                ? DoctrineOrderCriteria::ORDER_DESCENDING
                : DoctrineOrderCriteria::ORDER_ASCENDING;
            $queryBuilder->addOrderBy(sprintf('%s.%s', $rootAlias, $field), $order);
        }

        return true;
    }
}
