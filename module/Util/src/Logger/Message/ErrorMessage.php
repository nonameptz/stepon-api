<?php

namespace Util\Logger\Message;

use Doctrine\Common\Util\Debug;
use Util\Logger\FatalTracker;

/**
 * Class ErrorMessage
 *
 */
class ErrorMessage extends AbstractMessage
{
    const MESSAGE_FORMAT = '%s on line "%s" in file "%s"';
    const LABEL_FATAL    = 'Fatal error';
    const LABEL_ERROR    = 'Unhandled error';

    /**
     * ErrorMessage constructor.
     *
     * @param \Throwable $exception
     */
    public function __construct(\Throwable $exception)
    {
        parent::__construct();
        $this->payload = $exception;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        $date = new \DateTime();
        $date->setTimestamp(round(microtime(true)));
        $date->setTimezone(new \DateTimeZone('UTC'));
        return $date;
    }

    /**
     * @return string
     */
    public function getIndex()
    {
        return 'error';
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->getPayload()->getMessage();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $messageData = [
            'error'          => [
                'code'      => $this->defineCodeLabel($this->getPayload()->getCode()),
                'file'      => (string)$this->getPayload()->getFile(),
                'line'      => (string)$this->getPayload()->getLine(),
                'backtrace' => (string)$this->defineBacktrace(),
            ],
            'index'          => $this->getIndex(),
            'message'        => $this->toString(),
            'timestamp'      => $this->getTimestamp(),
            'requestId'      => $this->getRequestId(),
            'messageType'    => $this->defineType($this->getPayload()->getMessage()),
            'processedCount' => (int)$this->getProcessedCount(),
        ];

        $previous = $this->collectPrevious();
        if (!empty($previous)) {
            $messageData['error']['previousStack'] = $previous;
        }

        return $messageData;
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
        switch ($code) {
            case E_ERROR:
                return 'E_ERROR';
            case E_WARNING:
                return 'E_WARNING';
            case E_PARSE:
                return 'E_PARSE';
            case E_NOTICE:
                return 'E_NOTICE';
            case E_USER_ERROR:
                return 'E_USER_ERROR';
            case E_STRICT:
                return 'E_STRICT';
            case E_RECOVERABLE_ERROR:
                return 'E_RECOVERABLE_ERROR';
            case E_CORE_ERROR:
                return 'E_CORE_ERROR';
            case E_CORE_WARNING:
                return 'E_CORE_WARNING';
            case E_COMPILE_ERROR:
                return 'E_COMPILE_ERROR';
            case E_COMPILE_WARNING:
                return 'E_COMPILE_WARNING';
            case E_USER_WARNING:
                return 'E_USER_WARNING';
            case E_USER_NOTICE:
                return 'E_USER_NOTICE';
            case E_DEPRECATED:
                return 'E_DEPRECATED';
            case E_USER_DEPRECATED:
                return 'E_USER_DEPRECATED';
            case FatalTracker::CODE_UNHANDLED_EXCEPTION:
                return
                    'UNHANDLED_EXCEPTION';
            default:
                return 'UNDEFINED';
        }
    }

    /**
     * @param string $message
     *
     * @return string
     */
    protected function defineType($message)
    {
        if (false !== strpos($message, FatalTracker::TYPE_FATAL)) {
            return sprintf('%s.%s', $this->getIndex(), 'fatal');
        }
        if (false !== strpos($message, FatalTracker::TYPE_UNHANDLED_ERROR)) {
            return sprintf('%s.%s', $this->getIndex(), 'error');
        }
        if (false !== strpos($message, FatalTracker::TYPE_UNHANDLED_EXCEPTION)) {
            return sprintf('%s.%s', $this->getIndex(), 'exception');
        }

        return sprintf('%s.%s', $this->getIndex(), 'undefined');
    }

    /**
     * @return bool|string
     */
    private function defineBacktrace()
    {
        $trace = Debug::dump($this->payload->getTrace(), 3, true, false);
        return substr($trace, 0, 2048);
    }

    /**
     * @return string
     */
    private function collectPrevious()
    {
        $stackedMessages = '';
        $exception       = $this->getPayload();

        for ($count = 0; $count < 100; $count++) {
            $exception = $exception->getPrevious();
            if (!$exception instanceof \Throwable) {
                break;
            }

            $stackedMessages = sprintf(
                "message:%s\nfile:%s\nline:%s\n\n%s",
                $exception->getMessage(),
                $exception->getFile(),
                $exception->getLine(),
                $stackedMessages
            );
        }

        return substr($stackedMessages, 0, 2048);
    }
}
