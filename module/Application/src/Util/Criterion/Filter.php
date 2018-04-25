<?php

namespace Application\Util\Criterion;

use Application\Util\Criterion\Criteria\CriteriaInterface;
use IteratorAggregate;
use SplPriorityQueue;
use Traversable;
use Zend\Stdlib\Guard\ArrayOrTraversableGuardTrait;
use Application\Util\Criterion\Exception\InvalidArgumentException;

/**
 * Class Filter
 *
 * @package Application\Util\Criterion
 */
class Filter implements IteratorAggregate
{
    use ArrayOrTraversableGuardTrait;

    const LIMIT_PARAMETER_NAME    = 'limit';
    const OFFSET_PARAMETER_NAME   = 'offset';
    const ORDER_BY_PARAMETER_NAME = 'orderBy';

    /**
     * @var CriteriaInterface[]
     */
    protected $criteria = [];

    /**
     * Criteria priority
     *
     * @var array
     */
    protected $priority = [];

    /**
     * @var SplPriorityQueue|CriteriaInterface[]
     */
    protected $boundQueue;

    /**
     * @var CriteriaInterface[]
     */
    protected $boundRegistry = [];

    /**
     * Filter constructor.
     */
    public function __construct()
    {
        $this->boundQueue = new SplPriorityQueue();
    }

    /**
     * @param string            $name
     * @param CriteriaInterface $criteria
     * @param int               $priority
     *
     * @return $this
     */
    public function attachCriteria($name, CriteriaInterface $criteria, $priority = 1)
    {
        $this->criteria[$name] = $criteria;
        $this->priority[$name] = $priority;

        $criteria->setFilter($this);

        return $this;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasCriteria($name)
    {
        return isset($this->criteria[$name]);
    }

    /**
     * @param string $name
     *
     * @return CriteriaInterface
     */
    public function getCriteria($name)
    {
        if (!$this->hasCriteria($name)) {
            throw InvalidArgumentException::criteriaIsNotDefined($name);
        }

        return $this->criteria[$name];
    }

    /**
     * @param string            $name
     * @param CriteriaInterface $criteria
     * @param int               $priority
     *
     * @return self
     */
    public function attachAndBindCriteria($name, CriteriaInterface $criteria, $priority = 1)
    {
        $this->attachCriteria($name, $criteria, $priority)->bindCriteria($criteria, $priority);

        return $this;
    }

    /**
     * @param array|Traversable $params
     *
     * @return void
     */
    public function populate($params)
    {
        $this->guardForArrayOrTraversable($params);

        foreach ($params as $name => $value) {
            if (!$this->hasCriteria($name)) {
                continue;
            }

            $this->bindParam($name, $value);
        }
    }

    /**
     * Retrieve an external iterator
     *
     * @link  http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     *        <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return clone $this->boundQueue;
    }

    /**
     * @param CriteriaInterface $criteria
     * @param int               $priority
     */
    private function bindCriteria(CriteriaInterface $criteria, $priority)
    {
        $hash = spl_object_hash($criteria);

        if (in_array($hash, $this->boundRegistry)) {
            return;
        }

        $this->boundQueue->insert($criteria, $priority);
        array_push($this->boundRegistry, $hash);
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return self
     */
    private function bindParam($name, $value)
    {
        $criteria = $this->getCriteria($name);
        $priority = isset($this->priority[$name]) ? $this->priority[$name] : 1;

        $criteria->setValue($value);
        $this->bindCriteria($criteria, $priority);

        return $this;
    }
}
