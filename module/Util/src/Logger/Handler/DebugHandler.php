<?php

namespace Util\Logger\Handler;

use Util\Logger\Message\MessageInterface;

/**
 * Class DebugHandler
 *
 * @package Util\Logger\Handler
 */
class DebugHandler extends AbstractFileHandler
{
    /**
     * @param MessageInterface $message
     */
    public function handle(MessageInterface $message)
    {
        if (false === $this->canHandle($message)) {
            return;
        }

        $messageData = $message->toArray();

        if (!empty($this->helperData)) {
            $messageData = array_merge($this->helperData, $messageData);
        }

        $this->putContent(sprintf("%s\n", json_encode($messageData)));
    }
}
