<?php

namespace Util\Logger\Helper;

/**
 * Interface HelperInterface
 *
 * @package Util\Logger\Helper
 */
interface HelperInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return array
     */
    public function getData();
}
