<?php

namespace Util\Logger\Handler;

use Util\Logger\Message\ErrorMessage;
use Util\Logger\Message\MessageInterface;

/**
 * Class ErrorHandler
 *
 * @package Util\Logger\Handler
 */
class ErrorHandler extends AbstractFileHandler
{
    /**
     * @var array
     */
    protected $ignored = [];

    /**
     * @var
     */
    protected $cache = [];

    /**
     * @param array $config
     */
    public function init(array $config = [])
    {
        parent::init($config);

        if (isset($config['ignored']) && is_array($config['ignored'])) {
            $this->ignored = $config['ignored'];
        }
    }

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

        $messageData = $message->toArray();

        if (!empty($this->helperData)) {
            $messageData = array_merge($this->helperData, $messageData);
        }

        $contentString .= sprintf("%s\n", json_encode($messageData));

        $this->putContent($contentString);
    }

    /**
     * @param MessageInterface $message
     *
     * @return bool
     */
    protected function isIgnored(MessageInterface $message)
    {
        $exception = $message->getPayload();

        if (!$exception instanceof \Exception) {
            return true;
        }

        if ($message->isCanSkip()) {
            return true;
        }

        $key = $this->assembleKey($exception);
        if (isset($this->cache[$key])) {
            return $this->cache[$key];
        }

        $isIgnored = false;
        foreach ($this->ignored as $item) {
            $isIgnored = true;
            foreach ($item as $property => $value) {
                switch ($property) {
                    case 'message':
                        $isIgnored = $isIgnored && strpos($exception->getMessage(), $value) !== false;
                        break;
                    case 'level':
                        $isIgnored = $isIgnored && $value === $exception->getCode();
                        break;
                    case 'file':
                        $isIgnored = $isIgnored && strpos($exception->getFile(), $value) !== false;
                        break;
                    case 'line':
                        $isIgnored = $isIgnored && $value === $exception->getLine();
                        break;
                    default:
                        $isIgnored = false;
                }
            }

            if ($isIgnored === true) {
                break;
            }
        }

        $this->cache[$key] = $isIgnored;
        return $this->cache[$key];
    }

    /**
     * @param \Exception $exception
     *
     * @return string
     */
    protected function assembleKey(\Exception $exception)
    {
        return sha1(
            sprintf(
                '%u:%s:%s:%u', $exception->getCode(), $exception->getMessage(), $exception->getFile(),
                $exception->getLine()
            )
        );
    }
}
