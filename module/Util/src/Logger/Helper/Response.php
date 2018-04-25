<?php

namespace Util\Logger\Helper;

use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\ServerRequestFactory;

/**
 * Class Response
 *
 * @package Util\Logger\Helper
 */
class Response implements HelperInterface
{
    const NAME = 'response';

    /**
     * @var
     */
    private $request;

    /**
     * Response constructor.
     *
     * @throws \InvalidArgumentException
     */
    public function __construct()
    {
        //a lot of pain
        $this->request = ServerRequestFactory::fromGlobals();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     */
    public function getData()
    {
        return (PHP_SAPI === 'cli') ? $this->getCliResponse() : $this->getHttpResponse();
    }

    /**
     * @return ServerRequest
     * @throws \InvalidArgumentException
     */
    private function getRequest()
    {


        return $this->request;
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     */
    private function getHttpResponse()
    {
        return [
            'time' => $this->defineResponseTime(),
            'code' => $this->defineResponseCode(),
        ];
    }

    /**
     * @return int
     */
    private function defineResponseCode()
    {
        return http_response_code();
    }

    /**
     * @return int
     * @throws \InvalidArgumentException
     */
    private function defineResponseTime()
    {
        $requestTime  = round($this->getRequest()->getServerParams()['REQUEST_TIME_FLOAT'] * 1000);
        $responseTime = round(microtime(true) * 1000);
        return $responseTime - $requestTime;
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     */
    private function getCliResponse()
    {
        return [];
    }
}
