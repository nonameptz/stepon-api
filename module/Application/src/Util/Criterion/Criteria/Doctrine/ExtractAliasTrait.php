<?php
/**
 * File contains Trait ExtractAliasTrait
 *
 * @since   23.06.2016
 * @author  Anton Shepotko <anton.shepotko@veeam.com>
 */

namespace Application\Util\Criterion\Criteria\Doctrine;

use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Application\Util\Criterion\Exception\InvalidArgumentException;

/**
 * Trait ExtractAliasTrait
 *
 * @package Application\Util\Criterion\Criteria\Doctrine
 */
trait ExtractAliasTrait
{
    /**
     * @param QueryBuilder $queryBuilder
     * @param string       $subjectAlias
     * @param string       $rootAlias
     *
     * @return string
     * @throws InvalidArgumentException
     */
    protected function extractAlias(QueryBuilder $queryBuilder, $subjectAlias, $rootAlias = null)
    {
        $rootAlias = isset($rootAlias) ? $rootAlias : $this->extractRootAlias($queryBuilder);
        $alias     = null;

        if (empty($queryBuilder->getDQLPart('join')[$rootAlias])) {
            throw InvalidArgumentException::aliasNotFound($subjectAlias);
        }

        /** @var Join $join */
        foreach ($queryBuilder->getDQLPart('join')[$rootAlias] as $join) {
            if (preg_match('#' . $subjectAlias . '#i', $join->getJoin())) {
                $alias = $join->getAlias();
                break;
            }
        }

        if (empty($alias)) {
            throw InvalidArgumentException::aliasNotFound($subjectAlias);
        }

        return $alias;
    }

    /**
     * @param QueryBuilder $queryBuilder
     *
     * @return string
     */
    protected function extractRootAlias(QueryBuilder $queryBuilder)
    {
        $aliases = $queryBuilder->getRootAliases();
        return array_shift($aliases);
    }
}
