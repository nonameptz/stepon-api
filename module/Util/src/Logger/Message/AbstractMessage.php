<?php

namespace Util\Logger\Message;

use Doctrine\Common\Util\Debug;

/**
 * Class AbstractMessage
 *
 * @package Util\Logger\Message
 */
abstract class AbstractMessage implements MessageInterface
{
    /**
     * @var string
     */
    protected $id;

    /**
     * Dinamycally typed by passed data
     *
     * @var mixed
     */
    protected $payload;

    /**
     * @var int
     */
    protected $timestamp;

    /**
     * @var int
     */
    protected $processedCount = 0;

    /**
     * @var bool
     */
    protected $canSkip = false;

    /**
     * @var array
     */
    protected $context;

    /**
     * AbstractMessage constructor.
     */
    public function __construct()
    {
        $this->timestamp = round(microtime(true) * 1000);
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    public function setRequestId($id)
    {
        $this->id = (string)$id;
        return $this;
    }

    /**
     * @return string
     */
    public function getRequestId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @return int
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @return int
     */
    public function getProcessedCount()
    {
        return $this->processedCount;
    }

    /**
     * @param int $processedCount
     *
     * @return $this
     */
    public function setProcessedCount($processedCount)
    {
        $this->processedCount = $processedCount;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCanSkip()
    {
        return $this->canSkip;
    }

    /**
     * @param bool $value
     *
     * @return $this
     */
    public function setCanSkip($value)
    {
        $this->canSkip = $value;
        return $this;
    }

    /**
     * @param array $context
     *
     * @return $this
     */
    public function addContext(array $context)
    {
        $this->context = $context;
        return $this;
    }

    /**
     * @return array
     */
    protected function prepareContext()
    {
        if (empty($this->context)) {
            return [];
        }

        $context = Debug::dump($this->context, 3, true, false);
        return ['context' => substr($context, 0, 2048)];
    }
}
