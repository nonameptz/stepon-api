<?php

namespace Application\Util\Criterion\Criteria\Doctrine;

use Doctrine\ORM\QueryBuilder;
use Application\Util\Criterion\Exception\InvalidArgumentException;

/**
 * Class JoinTableTrait
 *
 * @package Util\Criterion\Criteria\Doctrine
 */
trait JoinTableTrait
{
    use ExtractAliasTrait;

    /**
     * @param QueryBuilder $queryBuilder
     * @param string       $rootAlias
     * @param string       $alias
     * @param string       $field
     * @param string       $join
     *
     * @return string
     */
    protected function joinTable(QueryBuilder $queryBuilder, $rootAlias, $alias, $field, $join = 'innerJoin')
    {
        try {
            $companyAlias = $this->extractAlias($queryBuilder, $field, $rootAlias);
        } catch (InvalidArgumentException $exception) {
            $companyAlias = $alias;
            $queryBuilder->$join(sprintf('%s.%s', $rootAlias, $field), $companyAlias);
        }

        return $companyAlias;
    }
}
