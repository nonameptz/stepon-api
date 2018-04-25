<?php

namespace Application\Hydrator\Strategy;

use Doctrine\ORM\EntityManager;
use Zend\Hydrator\Strategy\StrategyInterface;

/**
 * Class EntityStrategy
 *
 * @package Application\Hydrator\Strategy
 */
class EntityStrategy implements StrategyInterface
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var string
     */
    protected $entityClass;

    /**
     * @var string
     */
    protected $identifier;

    /**
     * @param EntityManager $em
     * @param mixed         $className
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function __construct(EntityManager $em, $className)
    {
        if (!is_string($className)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Provided entity class must be a string, [%s] given",
                    is_object($className) ? get_class($className) : gettype($className)
                )
            );
        }

        $this->em = $em;
        if (!class_exists($className) || !$this->isEntity($className)) {
            throw new \RuntimeException(
                sprintf(
                    "Provided entity class [%s] is not found",
                    $className
                )
            );
        }

        $this->entityClass = ltrim($className, "\\");

        /** @var \Doctrine\ORM\Mapping\ClassMetadata $meta */
        $meta = $this->em->getClassMetadata($this->entityClass);
        $this->identifier = $meta->getSingleIdentifierFieldName();
    }

    /**
     * Sets identifier for entity
     *
     * @param string $identifier
     *
     * @return $this
     */
    public function setIdentifier($identifier)
    {
        if (property_exists($this->entityClass, $identifier)) {
            $this->identifier = $identifier;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Converts the given value so that it can be extracted by the hydrator.
     *
     * @param mixed $value The original value.
     *
     * @throws \InvalidArgumentException
     * @return mixed Returns the value that should be extracted.
     */
    public function extract($value)
    {
        if (empty($value)) {
            return null;
        }

        $class = $this->entityClass;
        $isValidArgument = function ($input) use ($class) {
            if ($input instanceof $class) {
                return true;
            }

            if (!$input instanceof \Traversable) {
                return false;
            }

            foreach ($input as $entity) {
                if (!$entity instanceof $class) {
                    return false;
                }
            }

            return true;
        };

        if (!$isValidArgument($value)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Extracted value must be an instance of [%s] or a Collection of [%s] instances",
                    $this->entityClass,
                    $this->entityClass
                )
            );
        }

        return $this->extractValue($value, $class);
    }

    /**
     * Converts the given value so that it can be hydrated by the hydrator.
     *
     * @param mixed $value The original value.
     *
     * @return mixed Returns the value that should be hydrated.
     */
    public function hydrate($value)
    {
        if (empty($value)) {
            return null;
        }

        return is_array($value)
            ? $this->getEm()->getRepository($this->entityClass)->findBy([$this->identifier => $value])
            : $this->getEm()->getRepository($this->entityClass)->findOneBy([$this->identifier => $value]);
    }

    /**
     * Gets entity manager
     *
     * @return EntityManager
     */
    public function getEm()
    {
        return $this->em;
    }

    /**
     * Checks whether or not a provided class is Doctrine entity
     *
     * @param string $class
     *
     * @return bool
     */
    public function isEntity($class)
    {
        return !$this->em->getMetadataFactory()->isTransient($class);
    }

    /**
     * @param mixed $value
     * @param mixed $class
     *
     * @return array
     */
    protected function extractValue($value, $class)
    {
        $getter = 'get' . ucfirst($this->identifier);
        if ($value instanceof $class) {
            return $value->$getter();
        }

        $result = [];
        foreach ($value as $entity) {
            $result[] = $entity->$getter();
        }

        return $result;
    }
}
