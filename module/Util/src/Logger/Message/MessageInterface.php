<?php

namespace Util\Logger\Message;

/**
 * Interface MessageInterface
 *
 * @package Util\Logger\Message
 */
interface MessageInterface
{
    /**
     * @param string $id
     *
     * @return MessageInterface
     */
    public function setRequestId($id);

    /**
     * @return string
     */
    public function getRequestId();

    /**
     * @return mixed
     */
    public function getPayload();

    /**
     * @return string
     */
    public function toString();

    /**
     * @return array
     */
    public function toArray();

    /**
     * @return int
     */
    public function getProcessedCount();

    /**
     * @param int $skippedCount
     *
     * @return $this
     */
    public function setProcessedCount($skippedCount);

    /**
     * @return bool
     */
    public function isCanSkip();

    /**
     * @param bool $value
     *
     * @return $this
     */
    public function setCanSkip($value);
}
