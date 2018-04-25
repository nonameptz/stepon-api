<?php

namespace Util\Logger\Message;

use Util\Logger\Dto\RumDto;
use Util\Logger\Dto\RumErrorDto;

/**
 * Class RumErrorMessage
 *
 * @package Util\Logger\Message
 */
class RumErrorMessage extends AbstractMessage
{
    /**
     * @var RumDto
     */
    private $rumDto;

    /**
     * RequestMessage constructor.
     *
     * @param RumErrorDto $rumErrorDto
     * @param RumDto      $rumDto
     */
    public function __construct(RumErrorDto $rumErrorDto, RumDto $rumDto)
    {
        parent::__construct();
        $this->payload = $rumErrorDto;
        $this->rumDto  = $rumDto;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->payload->getMessage();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'error'       => [
                'code' => 'RUM_ERROR',
                'rum'  => $this->payload->toArray(),
            ],
            'rum'         => $this->rumDto->toArray(),
            'index'       => $this->getIndex(),
            'message'     => $this->toString(),
            'timestamp'   => $this->getTimestamp(),
            'requestId'   => $this->getRequestId(),
            'messageType' => 'RUM ERROR',
        ];
    }

    /**
     * @return string
     */
    public function getIndex()
    {
        return 'error';
    }
}
