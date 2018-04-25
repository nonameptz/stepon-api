<?php

namespace Application\Hydrator\Strategy;

use Zend\Hydrator\HydratorInterface;
use Zend\Hydrator\Strategy\StrategyInterface;

/**
 * Class SimpleHydratorStrategy
 *
 * @package Application\Hydrator\Strategy
 */
class SimpleHydratorStrategy implements StrategyInterface
{
    /**
     * @var HydratorInterface
     */
    protected $hydrator;

    /**
     * @var string
     */
    protected $className;

    /**
     * @var boolean
     */
    private $allowEmpty;

    /**
     * @param HydratorInterface $hydrator
     * @param string            $className
     * @param bool              $allowEmpty
     */
    public function __construct(HydratorInterface $hydrator, $className, $allowEmpty = false)
    {
        if (!class_exists($className)) {
            throw new \LogicException("Class {$className} does not exist");
        }
        $this->className  = $className;
        $this->hydrator   = $hydrator;
        $this->allowEmpty = (bool)$allowEmpty;
    }

    /**
     * Extracts values from provided object by hydrator
     *
     * @param object $object
     *
     * @return array
     */
    public function extract($object)
    {
        if (null === $object && $this->allowEmpty) {
            return null;
        }

        if (!$object instanceof $this->className) {
            throw new \RuntimeException(
                sprintf(
                    '%s method requires object instance of %s, but got %s',
                    __FUNCTION__,
                    $this->className,
                    is_object($object) ? get_class($object) : gettype($object)
                )
            );
        }
        return $this->hydrator->extract($object);
    }

    /**
     * Hydrates provided data to object
     *
     * @param mixed $data
     *
     * @return object
     */
    public function hydrate($data)
    {
        if (!is_array($data)) {
            throw new \RuntimeException(
                sprintf(
                    'Provided data must be an array, %s given',
                    is_object($data) ? get_class($data) : gettype($data)
                )
            );
        }
        return $this->hydrator->hydrate($data, new $this->className);
    }
}
