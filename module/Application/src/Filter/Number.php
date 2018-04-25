<?php

namespace Application\Filter;

use Zend\Filter\AbstractFilter;
use Zend\Filter\Exception;

/**
 * Class Number
 *
 * @package Application\Filter
 */
class Number extends AbstractFilter
{
    /**
     * @var int
     */
    private $precision = 0;
    /**
     * @var int
     */
    private $mode = PHP_ROUND_HALF_UP;

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->setOptions($options);
    }

    /**
     * @return int
     */
    public function getPrecision()
    {
        return $this->precision;
    }

    /**
     * @param int $precision
     */
    public function setPrecision($precision)
    {
        $this->precision = (int)$precision;
    }

    /**
     * @return int
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param int|string $mode
     */
    public function setMode($mode)
    {
        $modes = [
            'ROUND_HALF_UP'   => PHP_ROUND_HALF_UP,
            'ROUND_HALF_DOWN' => PHP_ROUND_HALF_DOWN,
            'ROUND_HALF_EVEN' => PHP_ROUND_HALF_EVEN,
            'ROUND_HALF_ODD'  => PHP_ROUND_HALF_ODD,
        ];
        if (is_integer($mode) === true) {
            $this->mode = $mode;
        } elseif (is_string($mode) === true && isset($modes[$mode]) === true) {
            $this->mode = $modes[$mode];
        }
    }

    /**
     * Returns the result of filtering $value
     *
     * @param  mixed $value Any scalar type
     *
     * @throws Exception\RuntimeException If filtering $value is impossible
     * @return float
     */
    public function filter($value)
    {
        if (is_string($value) === true) {
            $value = str_replace(',', '.', $value);
        } elseif (is_scalar($value) === false) {
            return $value;
        }
        if (is_numeric($value) === false) {
            return $value;
        }
        return round(floatval($value), $this->precision, $this->mode);
    }
}
