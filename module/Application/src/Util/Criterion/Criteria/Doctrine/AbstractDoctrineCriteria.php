<?php

namespace Application\Util\Criterion\Criteria\Doctrine;

use Application\Util\Criterion\Criteria\CriteriaInterface;
use Application\Util\Criterion\Filter;
use Doctrine\ORM\QueryBuilder;
use Application\Util\Criterion\Exception\InvalidArgumentException;

/**
 * Class AbstractDoctrineCriteria
 *
 * @package Application\Util\Criterion\Criteria\Doctrine
 */
abstract class AbstractDoctrineCriteria implements CriteriaInterface
{
    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var boolean
     */
    protected $applied = false;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @param Filter $filter
     *
     * @return void
     */
    public function setFilter(Filter $filter)
    {
        $this->filter = $filter;
    }

    /**
     * @return boolean
     */
    public function isApplied()
    {
        return $this->applied;
    }

    /**
     * @param mixed $value
     *
     * @return void
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $queryBuilder
     *
     * @return void
     */
    public function apply($queryBuilder)
    {
        if (!$queryBuilder instanceof QueryBuilder) {
            throw new InvalidArgumentException(
                sprintf(
                    'Instance of %s expected, got %s',
                    QueryBuilder::class,
                    is_object($queryBuilder) ? get_class($queryBuilder) : gettype($queryBuilder)
                )
            );
        }

        $aliases   = $queryBuilder->getRootAliases();
        $rootAlias = array_shift($aliases);

        $this->applied = (bool)$this->doApply($queryBuilder, $rootAlias);
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param string       $rootAlias
     *
     * @return bool TRUE if criteria was applied
     */
    abstract protected function doApply(QueryBuilder $queryBuilder, $rootAlias);
}
