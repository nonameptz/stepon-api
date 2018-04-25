<?php

namespace Util\Logger\Message;

/**
 * Class ExceptionMessage
 *
 * @package Util\Logger\Message
 */
class ExceptionMessage extends ErrorMessage
{
    /**
     * @return array
     */
    public function toArray()
    {
        $messageData = parent::toArray();
        $contextData = $this->prepareContext();
        return array_merge($contextData, $messageData);
    }

    /**
     * @param string $message
     *
     * @return string
     */
    protected function defineType($message)
    {
        return 'exception';
    }

    /**
     * Set error label by error code.
     *
     * @param int $code
     *
     * @return string
     */
    protected function defineCodeLabel($code)
    {
        return 'EXCEPTION';
    }
}
