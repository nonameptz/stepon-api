<?php

namespace Application\Util\Criterion\Criteria;

use Application\Util\Criterion\Filter;

/**
 * Interface CriteriaInterface
 *
 * @package Application\Util\Criterion\Criteria
 */
interface CriteriaInterface
{
    /**
     * @param Filter $filter
     *
     * @return void
     */
    public function setFilter(Filter $filter);

    /**
     * @param mixed $queryBuilder
     *
     * @return void
     */
    public function apply($queryBuilder);

    /**
     * @return boolean
     */
    public function isApplied();

    /**
     * @param mixed $value
     *
     * @return void
     */
    public function setValue($value);

    /**
     * @return mixed
     */
    public function getValue();
}
