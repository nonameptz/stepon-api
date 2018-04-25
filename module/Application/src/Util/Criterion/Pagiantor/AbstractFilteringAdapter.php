<?php

namespace Application\Util\Criterion\Pagiantor;

use Traversable;
use Application\Util\Criterion\Criteria\Doctrine\AbstractDoctrineCriteria;
use Application\Util\Criterion\Filter;
use Zend\Paginator\Adapter\AdapterInterface;
use Zend\Stdlib\Guard\ArrayOrTraversableGuardTrait;

/**
 * Class AbstractFilteringAdapter
 *
 * @package Application\Util\Criterion\Pagiantor
 */
abstract class AbstractFilteringAdapter implements AdapterInterface
{

    use ArrayOrTraversableGuardTrait;

    /**
     * @var Filter
     */
    private $filter;

    /**
     * @var array
     */
    protected $items = [];

    /**
     * @var int
     */
    protected $count = null;

    /**
     * @var AbstractDoctrineCriteria
     */
    private $orderCriteria;

    /**
     * AbstractFilteringAdapter constructor.
     *
     * @param Filter $filter
     */
    public function __construct(Filter $filter)
    {
        $this->filter = $filter;
    }

    /**
     * @param array|Traversable $params
     */
    public function setCriteria($params)
    {
        $this->guardForArrayOrTraversable($params);

        $this->filter->populate($params);
        $this->items = [];
        $this->count = null;
    }

    /**
     * @inheritDoc
     */
    public function getItems($offset, $itemCountPerPage)
    {
        $segmentKey = $this->getSegmentKey($offset, $itemCountPerPage);
        if (!$this->hasSegment($segmentKey)) {
            $filter = clone $this->filter;
            $filter->populate(
                [
                    Filter::LIMIT_PARAMETER_NAME  => $itemCountPerPage,
                    Filter::OFFSET_PARAMETER_NAME => $offset,
                ]
            );

            $result                   = $this->internalGetItems($filter);
            $this->items[$segmentKey] = array_map([$this, 'convert'], $result);
        }

        return $this->getSegment($segmentKey);
    }

    /**
     * @inheritDoc
     */
    public function count()
    {
        if (null === $this->count) {
            $filter      = clone $this->filter;
            $this->count = $this->internalCount($filter);
        }

        return $this->count;
    }

    /**
     * @return AbstractDoctrineCriteria
     */
    public function getOrderCriteria()
    {
        return $this->orderCriteria;
    }

    /**
     * @param AbstractDoctrineCriteria $orderCriteria
     *
     * @return $this
     */
    public function setOrderCriteria(AbstractDoctrineCriteria $orderCriteria)
    {
        $this->orderCriteria = $orderCriteria;

        return $this;
    }

    /**
     * @param mixed $item
     *
     * @return mixed
     */
    protected function convert($item)
    {
        return $item;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    protected function hasSegment($key)
    {
        return isset($this->items[$key]);
    }

    /**
     * @param string $key
     *
     * @return null|array
     */
    protected function getSegment($key)
    {
        if (!$this->hasSegment($key)) {
            return null;
        }

        return $this->items[$key];
    }

    /**
     * @param int $offset
     * @param int $itemCountPerPage
     *
     * @return string
     */
    protected function getSegmentKey($offset, $itemCountPerPage)
    {
        return sprintf('%d.%d', $offset, $itemCountPerPage);
    }

    /**
     * Performs actual data request
     *
     * @param Filter $filter
     *
     * @return array
     */
    abstract protected function internalGetItems(Filter $filter);

    /**
     * Performs data resulting count
     *
     * @param Filter $filter
     *
     * @return int
     */
    abstract protected function internalCount(Filter $filter);
}
