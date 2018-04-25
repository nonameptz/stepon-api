<?php

namespace Application\Util\Criterion\Exception;

use InvalidArgumentException as BaseException;

/**
 * Class InvalidArgumentException
 *
 * @package Application\Util\Criterion\Exception
 */
class InvalidArgumentException extends BaseException
{
    /**
     * @param string $paramName
     *
     * @return self
     */
    public static function criteriaIsNotDefined($paramName)
    {
        return new static(sprintf('Criteria for %s is not defined', $paramName));
    }

    /**
     * @param string $relation
     *
     * @return self
     */
    public static function aliasNotFound($relation)
    {
        return new static(sprintf('`%s` entity relation MUST be defined in `JOIN` section', $relation));
    }
}
