<?php

namespace Util\Logger\Handler;

use Util\Logger\Message\ErrorMessage;
use Util\Logger\Message\MessageInterface;

/**
 * Class FatalErrorHandler
 *
 * @package Util\Logger\Handler
 */
class FatalErrorHandler extends ErrorHandler
{
    /**
     * @param MessageInterface $message
     */
    public function handle(MessageInterface $message)
    {
        $contentString = '';

        /** @var ErrorMessage $message */
        if (false === $this->canHandle($message)) {
            return;
        }

        if (true === $this->isIgnored($message)) {
            return;
        }

        $counterString = '';
        if ($message->getProcessedCount() > 1) {
            $counterString = sprintf(' [%u total processed]', $message->getProcessedCount());
        }

        $contentString .= sprintf(
            "[%s] %s%s\n",
            $message->getDate()->format('Y-m-d h:i:s'),
            $message->toString(), $counterString
        );

        $this->putContent($contentString);
    }

}
