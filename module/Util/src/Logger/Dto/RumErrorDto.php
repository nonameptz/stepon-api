<?php

namespace Util\Logger\Dto;

/**
 * Class RumErrorDto
 *
 * @package Util\Logger\Dto
 */
class RumErrorDto
{
    /**
     * @var int
     */
    protected $timestamp;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var int
     */
    protected $code;

    /**
     * @var string
     */
    protected $source;

    /**
     * @var array
     */
    protected $events;

    /**
     * @var array
     */
    protected $trace;

    /**
     * @var int
     */
    protected $count;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $via;

    /**
     * @param string $timestamp
     *
     * @return $this
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = (int)base_convert($timestamp, 36, 10);
        return $this;
    }

    /**
     * @param string $message
     *
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param int $code
     *
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = (int)$code;
        return $this;
    }

    /**
     * @param int $count
     *
     * @return $this
     */
    public function setCount($count)
    {
        $this->count = (int)$count;
        return $this;
    }

    /**
     * @param string $source
     *
     * @return $this
     */
    public function setSource($source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @param array $trace
     *
     * @return $this
     */
    public function setTrace(array $trace)
    {
        if (!empty($trace)) {
            $stacktrace = [];

            foreach ($trace as $errorNumber => $error) {
                $stacktrace[$errorNumber] = [
                    'line'     => !empty($error['l']) ? $error['l'] : '',
                    'column'   => !empty($error['c']) ? $error['c'] : '',
                    'function' => !empty($error['f']) ? $error['f'] : '',
                    'file'     => !empty($error['w']) ? $error['w'] : '',
                ];
            }

            $this->trace = $stacktrace;
        }

        return $this;
    }

    /**
     * @param array $events
     *
     * @return $this
     */
    public function setEvents(array $events)
    {
        if (!empty($events)) {
            $eventTrace = [];

            foreach ($events as $eventNumber => $event) {
                if (!empty($event['t'])) {
                    $eventTrace[$eventNumber]['type'] = $event['t'];
                    unset($event['t']);
                }

                if (!empty($event['d'])) {
                    $eventTrace[$eventNumber]['timestamp'] = $event['d'];
                    unset($event['d']);
                }

                $eventTrace[$eventNumber]['metadata'] = $event;
            }

            $this->events = $eventTrace;
        }

        return $this;
    }

    /**
     * @param int $viaCode
     *
     * @return $this
     */
    public function setVia($viaCode)
    {
        $via = $this->convertViaValue($viaCode);
        if (!empty($via)){
            $this->via = $via;
        }

        return $this;
    }

    /**
     * @param $viaCode
     *
     * @return mixed
     */
    protected function convertViaValue($viaCode){
        $viaValues = [
            1 => 'VIA_APP',
            2 => 'VIA_GLOBAL_EXCEPTION_HANDLER',
            3 => 'VIA_NETWORK',
            4 => 'VIA_CONSOLE',
            5 => 'VIA_EVENTHANDLER',
            6 => 'VIA_TIMEOUT',
        ];

        return $viaValues[$viaCode];
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param array $error
     *
     * @return RumErrorDto
     */
    public static function factory(array $error)
    {
        $rumErrorDto = new self();

        if (!empty($error['n'])) {
            $rumErrorDto->setCount($error['n']);
        }

        if (!empty($error['d'])) {
            $rumErrorDto->setTimestamp($error['d']);
        }

        if (!empty($error['m'])) {
            $rumErrorDto->setMessage($error['m']);
        }

        if (!empty($error['s'])) {
            $rumErrorDto->setSource($error['s']);
        }

        if (!empty($error['f'])) {
            $rumErrorDto->setTrace($error['f']);
        }

        if (!empty($error['e'])) {
            $rumErrorDto->setEvents($error['e']);
        }

        if (!empty($error['v'])) {
            $rumErrorDto->setVia($error['v']);
        }

        if (!empty($error['c'])) {
            $rumErrorDto->setCode($error['c']);
        }

        if (!empty($error['t'])) {
            $rumErrorDto->setType($error['t']);
        }

        return $rumErrorDto;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $result = [];
        foreach (get_object_vars($this) as $name => $value) {
            if (!empty($value)) {
                $result[$name] = $value;
            }
        }
        return $result;
    }
}