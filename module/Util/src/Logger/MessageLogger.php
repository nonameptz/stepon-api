<?php

namespace Util\Logger;

use Util\Logger\Handler\HandlerInterface;
use Util\Logger\Helper\HelperInterface;
use Util\Logger\Message\MessageInterface;
use Util\Logger\Throughput\MemoryProcessor;

/**
 * Class MessageLogger
 *
 * @package Util\Logger
 */
class MessageLogger
{
    /**
     * @var MessageLogger
     */
    private static $instance;

    /**
     * @var MemoryProcessor
     */
    private static $memoryProcessor;

    /**
     * @var string
     */
    private $requestId;

    /**
     * @var HelperInterface[]
     */
    private $helpers = [];

    /**
     * @var HandlerInterface[]
     */
    private $handlers = [];

    /**
     * @return MessageLogger
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param MessageInterface $message
     *
     * @return $this
     */
    public function pushMessage(MessageInterface $message)
    {
        $message->setRequestId($this->requestId);

        self::$memoryProcessor->process($message);
        if ($message->isCanSkip()) {
            return $this;
        }

        foreach ($this->handlers as $handler) {
            if (!empty($this->helpers)) {
                $handler->pushHelpers($this->helpers);
            }

            try {
                $handler->handle($message);
            } catch (\Exception $exception) {
                continue;
            }
        }

        return $this;
    }

    /**
     * @param HandlerInterface $handler
     *
     * @return $this
     */
    public function pushHandler(HandlerInterface $handler)
    {
        if (!is_array($this->handlers)) {
            $this->handlers = [];
        }

        $this->handlers[] = $handler;
        return $this;
    }

    /**
     * @param HelperInterface $helper
     *
     * @return $this
     */
    public function pushHelper(HelperInterface $helper)
    {
        if (!is_array($this->helpers)) {
            $this->helpers = [];
        }

        $this->helpers[] = $helper;
        return $this;
    }

    /**
     * MessageLogger constructor.
     */
    private function __construct()
    {
        $this->requestId       = uniqid('', true);
        self::$memoryProcessor = new MemoryProcessor();
    }

    private function __clone()
    {
    }
}
