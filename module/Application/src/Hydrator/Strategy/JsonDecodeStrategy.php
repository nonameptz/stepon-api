<?php

namespace Application\Hydrator\Strategy;

use Zend\Hydrator\Strategy\StrategyInterface;

/**
 * Class JsonDecodeStrategy
 *
 * @package Api\Invoice\V1\Lib\Invoice\Strategy
 */
class JsonDecodeStrategy implements StrategyInterface
{
    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function extract($value)
    {
        return json_decode($value);
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function hydrate($value)
    {
        return $value;
    }
}