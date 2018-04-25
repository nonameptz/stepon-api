<?php

namespace Application\Hydrator\Strategy;

use DateTime;
use InvalidArgumentException;
use RuntimeException;
use Zend\Hydrator\Strategy\StrategyInterface;

/**
 * Class DateTimeStrategy
 *
 * @package Application\Hydrator\Strategy
 */
class DateTimeStrategy implements StrategyInterface
{
    /**
     * @var string
     */
    protected $format = \DateTime::ISO8601;

    /**
     * @var \DateTimeZone
     */
    protected $timezone;

    /**
     * @param string $format
     */
    public function __construct($format = null)
    {
        if (isset($format)) {
            $this->setFormat($format);
        }
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param string $format
     *
     * @return self
     */
    public function setFormat($format)
    {
        $this->format = (string)$format;
        return $this;
    }

    /**
     * @return \DateTimeZone
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * @param \DateTimeZone $timezone
     *
     * @return $this
     */
    public function setTimezone(\DateTimeZone $timezone)
    {
        $this->timezone = $timezone;
        return $this;
    }

    /**
     * Converts the given value so that it can be extracted by the hydrator.
     *
     * @param DateTime|mixed $value The original value.
     *
     * @return string
     * @throws InvalidArgumentException
     */
    public function extract($value)
    {
        if ($value instanceof DateTime) {
            return $value->format($this->format);
        } else {
            return $value;
        }
    }

    /**
     * Converts the given value so that it can be hydrated by the hydrator.
     *
     * @param DateTime|string $value The original value.
     *
     * @return DateTime
     * @throws RuntimeException
     * @throws InvalidArgumentException
     */
    public function hydrate($value)
    {
        if ($value instanceof DateTime || $value === null) {
            return $value;
        }

        if (!is_string($value)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Provided value must be an instance of DateTime object or a string, [%s] given',
                    (is_object($value) ? get_class($value) : gettype($value))
                )
            );
        }
        if ($this->getTimezone() instanceof \DateTimeZone) {
            $date = DateTime::createFromFormat($this->format, $value, $this->getTimezone());
        } else {
            $date = DateTime::createFromFormat($this->format, $value);
        }

        if ($date === false) {
            throw new RuntimeException(sprintf('The value "%s" does not fit the format "%s"', $value, $this->format));
        }

        return $date;
    }
}
