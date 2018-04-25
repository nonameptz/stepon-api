<?php

namespace Application\Util;

use Zend\InputFilter\InputFilterInterface;
use Zend\Mvc\MvcEvent;

/**
 * Trait InputFilterExtractorTrait
 *
 * @package Application\Util
 */
trait InputFilterExtractorTrait
{
    /**
     * @param MvcEvent $event
     *
     * @return InputFilterInterface
     */
    protected function getInputFilter(MvcEvent $event)
    {
        $inputFilter = $event->getParam('ZF\ContentValidation\InputFilter');
        if (!($inputFilter instanceof InputFilterInterface)) {
            throw new \RuntimeException('Unable to get input filter');
        }
        return $inputFilter;
    }
}
