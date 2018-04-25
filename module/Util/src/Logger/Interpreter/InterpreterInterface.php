<?php

namespace Util\Logger\Interpreter;

use Util\Logger\Event\Event;

/**
 * Interface InterpreterInterface
 *
 * @package Util\Logger\Interpreter
 */
interface InterpreterInterface
{
    /**
     * @param array|\stdClass $data
     * @param Event           $event
     *
     * @return void
     */
    public function interpret($data, Event $event);
}
