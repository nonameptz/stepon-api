<?php

namespace Util\Logger\Message;

use Util\Logger\Converter\Converter;
use Util\Logger\Event\Event;

/**
 * Class EventMessage
 *
 * @package Util\Logger\Message
 */
class EventMessage extends AbstractMessage
{

    /**
     * EventMessage constructor.
     *
     * @param Event $event
     */
    public function __construct(Event $event)
    {
        parent::__construct();
        $this->payload = $event;
    }

    /**
     * @return string
     */
    public function getIndex()
    {
        return 'application';
    }

    /**
     * @return string
     */
    public function toString()
    {
        return sprintf(
            'Event `%s` registered',
            $this->payload->getLabel()
        );
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'event'       => Converter::convert($this->getPayload()),
            'index'       => $this->getIndex(),
            'timestamp'   => $this->getTimestamp(),
            'requestId'   => $this->getRequestId(),
            'messageType' => 'EVENT',
        ];
    }

}
