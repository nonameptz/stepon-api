<?php

namespace Util\Logger\Message;

use Psr\Http\Message\UriInterface;
use Util\Logger\Dto\UrlDto;
use Zend\Uri\Uri;

/**
 * Class ExternalServiceMessage
 *
 * @package Util\Logger\Message
 */
class ExternalServiceMessage extends AbstractMessage
{
    const MESSAGE = 'External request processed';

    /**
     * @var string
     */
    protected $service;

    /**
     * @var UriInterface
     */
    protected $url;

    /**
     * @var string
     */
    protected $method;

    /**
     * @var int
     */
    protected $requestTime;

    /**
     * ExternalServiceMessage constructor.
     *
     * @param string $service
     * @param string $url
     * @param string $method
     * @param int    $requestTimer
     */
    public function __construct($service, $url, $method, $requestTimer)
    {
        parent::__construct();
        $this->payload     = self::MESSAGE;
        $this->service     = (string)$service;
        $this->url         = new Uri((string)$url);
        $this->method      = (string)$method;
        $this->requestTime = (int)$requestTimer;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return $this->payload;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $messageData = [
            'index'           => $this->getIndex(),
            'message'         => $this->getPayload(),
            'timestamp'       => $this->getTimestamp(),
            'requestId'       => $this->getRequestId(),
            'messageType'     => 'OUTCOMING REQUEST',
            'externalService' => [
                'service'     => $this->service,
                'requestTime' => $this->requestTime,
                'method'      => $this->method,
                'url'         => UrlDto::fromUri($this->url)->toArray(false),
            ],
        ];
        $contextData = $this->prepareContext();
        return array_merge($contextData, $messageData);
    }

    /**
     * @return string
     */
    public function getIndex()
    {
        return 'application';
    }
}
