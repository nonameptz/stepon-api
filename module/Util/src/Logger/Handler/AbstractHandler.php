<?php

namespace Util\Logger\Handler;

use Util\Logger\Helper\HelperInterface;
use Util\Logger\Message\MessageInterface;

/**
 * Class AbstractHandler
 *
 * @package Util\Logger\Handler
 */
abstract class AbstractHandler implements HandlerInterface
{
    /**
     * @var array
     */
    protected $processable;

    /**
     * @var array
     */
    protected $possibleHelpers = [];

    /**
     * @var array
     */
    protected $helperData = [];

    /**
     * @param array $config
     */
    public function init(array $config = [])
    {
        if (isset($config['processable']) && is_array($config['processable'])) {
            $this->processable = $config['processable'];
        }

        if (isset($config['helpers']) && is_array($config['helpers'])) {
            $this->possibleHelpers = $config['helpers'];
        }
    }

    /**
     * @param HelperInterface[] $helpers
     */
    public function pushHelpers(array $helpers)
    {
        foreach ($helpers as $helper) {
            if (false === $this->canAttach($helper)) {
                continue;
            }

            if (empty($helper->getData())) {
                continue;
            }

            $this->helperData[$helper->getName()] = $helper->getData();
        }
    }

    /**
     * @param MessageInterface $message
     *
     * @return bool
     */
    protected function canHandle(MessageInterface $message)
    {
        if (empty($this->processable)) {
            return false;
        }

        foreach ($this->processable as $processableClass) {
            if (get_class($message) === $processableClass) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param HelperInterface $helper
     *
     * @return bool
     */
    protected function canAttach(HelperInterface $helper)
    {
        if (empty($this->possibleHelpers)) {
            return false;
        }

        foreach ($this->possibleHelpers as $helperClass) {
            if ($helper instanceof $helperClass) {
                return true;
            }
        }

        return false;
    }
}
