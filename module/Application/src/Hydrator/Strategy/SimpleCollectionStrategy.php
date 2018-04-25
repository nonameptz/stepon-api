<?php
namespace Application\Hydrator\Strategy;

use Zend\Stdlib\Guard\ArrayOrTraversableGuardTrait;

class SimpleCollectionStrategy extends SimpleHydratorStrategy
{
    use ArrayOrTraversableGuardTrait;

    /**
     * @var string
     */
    protected $indexBy;

    /**
     * @return string
     */
    public function getIndexBy()
    {
        return $this->indexBy;
    }

    /**
     * @param string $indexBy
     *
     * @return self
     */
    public function setIndexBy($indexBy)
    {
        if (!method_exists($this->className, $indexBy)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The method %s does not belong to the %s',
                    $indexBy,
                    $this->className
                )
            );
        }

        $this->indexBy = $indexBy;
        return $this;
    }

    /**
     * @param array|\Traversable $value The original value.
     *
     * @return array Returns the value that should be extracted.
     */
    public function extract($value)
    {
        $this->guardForArrayOrTraversable($value);

        $result = [];
        foreach ($value as $item) {
            isset($this->indexBy) ?
                $result[$item->{$this->indexBy}()] = parent::extract($item)
                : $result[] = parent::extract($item);
        }
        return $result;
    }

    /**
     * @param \stdClass $value The original value.
     *
     * @return array
     */
    public function hydrate($value)
    {
        $this->guardForArrayOrTraversable($value);

        $result = [];
        foreach ($value as $item) {
            $object = parent::hydrate($item);
            isset($this->indexBy) ?
                $result[$object->{$this->indexBy}()] = $object
                : $result[] = $object;
        }
        return $result;
    }
}
