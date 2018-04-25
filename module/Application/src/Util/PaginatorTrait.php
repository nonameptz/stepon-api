<?php

namespace Application\Util;

use Application\Util\Criterion\Pagiantor\AbstractFilteringAdapter;
use Zend\Paginator\Paginator;

trait PaginatorTrait
{
    /**
     * @param array                    $params
     * @param AbstractFilteringAdapter $adapter
     *
     * @return Paginator
     */
    protected function configurePaginator(array $params = [], AbstractFilteringAdapter $adapter, $limit, $page)
    {
        $limit = $limit < 1 ? self::DEFAULT_LIMIT : $limit;
        $page  = $page < 1 ? self::DEFAULT_PAGE : $page;

        $params = array_merge(['limit' => $limit, 'page' => $page], $params);
        $adapter->setCriteria($params);
        $paginator = new Paginator($adapter);

        $paginator->setItemCountPerPage($limit);
        $paginator->setCurrentPageNumber($page);

        return $paginator;
    }
}