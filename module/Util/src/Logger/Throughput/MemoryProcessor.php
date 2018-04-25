<?php

namespace Util\Logger\Throughput;

use Util\Logger\Message\MessageInterface;

/**
 * Class MemoryProcessor
 *
 * @package Util\Logger\Throughput
 */
class MemoryProcessor
{
    /**
     * @var array
     */
    private $processed = [];

    /**
     * @param MessageInterface $message
     */
    public function process(MessageInterface $message)
    {
        $payload = $message->getPayload();
        if ($payload instanceof \Exception) {
            $key = md5(implode('|', [$payload->getFile(), $payload->getLine()]));

            if (isset($this->processed[$key])) {
                $message->setCanSkip(true);
                $this->processed[$key] += 1;
                return;
            }

            $this->processed[$key] = 1;
        }
    }
}
