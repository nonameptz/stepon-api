<?php

namespace Util\Logger\Message;

/**
 * Class DebugMessage
 *
 * @package Util\Logger\Message
 */
class DebugMessage extends AbstractMessage
{
    /**
     * DebugMessage constructor.
     *
     * @param $message
     */
    public function __construct($message)
    {
        parent::__construct();
        $this->payload = (string)$message;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->getPayload();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $messageData = [
            'index'       => $this->getIndex(),
            'message'     => $this->getPayload(),
            'timestamp'   => $this->getTimestamp(),
            'requestId'   => $this->getRequestId(),
            'messageType' => 'DEBUG',
        ];
        $contextData = $this->prepareContext();
        return array_merge($contextData, $messageData);
    }

    /**
     * @return string
     */
    public function getIndex()
    {
        return 'application';
    }
}
