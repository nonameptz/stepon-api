<?php

namespace Application\Hydrator\Strategy;

use Zend\Hydrator\NamingStrategy\NamingStrategyInterface;

/**
 * Class MethodsMapNamingStrategy
 *
 * @package Application\Hydrator\Strategy
 */
class MethodsMapNamingStrategy implements NamingStrategyInterface
{

    /**
     * @var array
     */
    protected $hydrationMap = [];

    /**
     * @var array
     */
    protected $extractionMap = [];

    /**
     * MethodsMapNamingStrategy constructor.
     *
     * @param array $hydrationMap
     * @param array $extractionMap
     */
    public function __construct(array $hydrationMap, array $extractionMap)
    {
        $this->hydrationMap  = $hydrationMap;
        $this->extractionMap = $extractionMap;
    }

    /**
     * Converts the given name so that it can be extracted by the hydrator.
     *
     * @param string $name   The original name
     * @param object $object (optional) The original object for context.
     *
     * @return mixed         The hydrated name
     */
    public function hydrate($name)
    {
        if (array_key_exists($name, $this->hydrationMap)) {
            return $this->hydrationMap[$name];
        }

        return $name;
    }

    /**
     * Converts the given name so that it can be hydrated by the hydrator.
     *
     * @param string $name The original name
     * @param array  $data (optional) The original data for context.
     *
     * @return mixed The extracted name
     */
    public function extract($name)
    {
        if (array_key_exists($name, $this->extractionMap)) {
            return $this->extractionMap[$name];
        }

        return $name;
    }
}
