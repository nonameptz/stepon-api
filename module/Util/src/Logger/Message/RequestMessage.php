<?php

namespace Util\Logger\Message;

/**
 * Class RequestMessage
 *
 * @package Util\Logger\Message
 */
class RequestMessage extends AbstractMessage
{
    /**
     * RequestMessage constructor.
     *
     * @param string $message
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
        return $this->payload;
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
            'messageType' => 'INCOMING REQUEST',
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
