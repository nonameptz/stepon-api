<?php

namespace Util\Logger\Event;

use Util\Logger\Interpreter\InterpreterInterface;

/**
 * Class Event
 *
 * @package Util\Logger\Event
 */
class Event
{
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED  = 'failed';

    /**
     * @var string
     */
    private $timestamp;

    /**
     * @var string
     */
    private $group;

    /**
     * @var string
     */
    private $action;

    /**
     * @var array
     */
    private $data;

    /**
     * @var array|\stdClass
     */
    private $response;

    /**
     * @var array
     */
    private $error;

    /**
     * @var string
     */
    private $status;

    /**
     * @var array
     */
    private $singleValues;

    /**
     * Event constructor.
     *
     * @param string $group
     * @param string $action
     */
    public function __construct($group, $action)
    {
        $this->group        = $group;
        $this->action       = $action;
        $this->timestamp    = round(microtime(true) * 1000);
        $this->error        = [];
        $this->singleValues = [];
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array                $data
     * @param InterpreterInterface $interpreter
     *
     * @return $this
     */
    public function setData(array $data, InterpreterInterface $interpreter = null)
    {
        if (null === $interpreter) {
            $this->data = $data;
            return $this;
        }

        $interpreter->interpret($data, $this);
        return $this;
    }

    /**
     * @return array
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param array|\stdClass      $response
     * @param InterpreterInterface $interpreter
     *
     * @return $this
     */
    public function setResponse($response, InterpreterInterface $interpreter = null)
    {
        $this->setStatus(self::STATUS_SUCCESS);
        if (null === $interpreter) {
            $this->response = $response;
            return $this;
        }

        $interpreter->interpret($response, $this);
        return $this;
    }

    /**
     * @return string
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @return string
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status !== null ? $this->status : '';
    }

    /**
     * @param string $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return array
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param array|\Exception $error
     *
     * @return $this
     */
    public function setError($error)
    {
        $this->error = [];
        $this->addError($error);
        return $this;
    }

    /**
     * @param $error
     *
     * @return $this
     */
    public function addError($error)
    {
        $this->setStatus(Event::STATUS_FAILED);

        if (!$error instanceof \Exception) {
            $this->error[] = $error;
            return $this;
        }

        $this->error[] = $error->getMessage();
        return $this;
    }

    /**
     * @param string $source
     *
     * @return $this
     */
    public function setSource($source)
    {
        $this->addSingleValue('source', $source);
        return $this;
    }

    /**
     * @param string     $name
     * @param int|string $value
     */
    public function addSingleValue($name, $value)
    {
        $key                      = sprintf('%s.%s', $this->getLabel(), $name);
        $this->singleValues[$key] = $value;
    }

    /**
     * @return array
     */
    public function getSingleValues()
    {
        return $this->singleValues;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        $label = sprintf('%s.%s', $this->getAction(), $this->getGroup());
        return strtolower($label);
    }
}

