<?php

namespace Util\Logger\Handler;

use Util\Logger\Helper\HelperInterface;
use Util\Logger\Message\MessageInterface;

/**
 * Interface HandlerInterface
 *
 * @package Util\Logger\Handlers
 */
interface HandlerInterface
{
    /**
     * @param array $config
     */
    public function init(array $config = []);

    /**
     * @param HelperInterface[] $helpers
     */
    public function pushHelpers(array $helpers);

    /**
     * @param MessageInterface $message
     */
    public function handle(MessageInterface $message);
}
