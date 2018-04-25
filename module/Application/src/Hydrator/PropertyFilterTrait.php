<?php

namespace Application\Hydrator;

use Zend\Hydrator\Filter\FilterComposite;
use Zend\Hydrator\Filter\MethodMatchFilter;

/**
 * Class PropertyFilterTrait
 *
 * @package Application\Hydrator
 * @author  Anton Shepotko <anton.shepotko@veeam.com>
 */
trait PropertyFilterTrait
{
    /**
     * @param array $properties
     * @param bool  $exclude
     * @param array $prefixes
     *
     * @return FilterComposite
     */
    public function getPropertyFilter($properties, $exclude = true, array $prefixes = ['get'])
    {
        $filter = new FilterComposite();
        foreach ($properties as $property) {
            foreach ($prefixes as $prefix) {
                $name = $prefix . ucfirst($property);
                $filter->addFilter(
                    $name,
                    new MethodMatchFilter($name, $exclude),
                    $exclude === true ? FilterComposite::CONDITION_AND : FilterComposite::CONDITION_OR
                );
            }
        }
        return $filter;
    }
}
