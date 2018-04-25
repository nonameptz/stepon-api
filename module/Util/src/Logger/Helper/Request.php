<?php

namespace Util\Logger\Helper;

use Application\Util\IPUtils;
use Util\Logger\Dto\UrlDto;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\ServerRequestFactory;

/**
 * Class Request
 *
 * @author Matvey Podgornov <matvey.podgornov@veeam.com>
 */
class Request implements HelperInterface
{
    const NAME               = 'request';
    const COUNTRY_COOKIE     = 'clientCountry';
    const COUNTRY_COOKIE_TTL = 1800; // 60 * 30
    const LAST_IP            = 'clientIP';
    const LAST_IP_TTL        = 1800;

    /**
     * @var
     */
    private $request;

    /**
     * @var array
     */
    private $requestData;

    /**
     * Response constructor.
     *
     * @throws \InvalidArgumentException
     */
    public function __construct()
    {
        $this->requestData = (PHP_SAPI === 'cli') ? $this->getCliRequest() : $this->getHttpRequest();
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
        return $this->requestData;
    }

    /**
     * @return ServerRequest
     * @throws \InvalidArgumentException
     */
    private function getRequest()
    {
        if (!isset($this->request)) {
            $this->request = ServerRequestFactory::fromGlobals();
        }

        return $this->request;
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     */
    private function getHttpRequest()
    {
        $result  = [];
        $request = $this->getRequest();

        $bodyParams = !empty($request->getParsedBody()) ? $request->getParsedBody()
            : (string)$request->getBody();
        if (!empty($bodyParams)) {
            $result['bodyParams'] = substr(json_encode($bodyParams), 0, 1024);
        }

        $headers = $this->extractHeaders($request->getHeaders());
        if (!empty($headers)) {
            $result['headers'] = substr($headers, 0, 1024);
        }

        $cookies = $this->extractCookies($request->getHeaders());
        if (!empty($cookies)) {
            $result['cookies'] = substr($cookies, 0, 1024);
        }

        $result['isAjax'] = isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        if (isset($_COOKIE[self::COUNTRY_COOKIE])) {
            $result['clientCountry'] = $_COOKIE[self::COUNTRY_COOKIE];
        }

        return array_merge(
            $result,
            [
                'httpMethod'  => (string)$request->getMethod(),
                'clientIp'    => (string)IPUtils::extractClientIp(),
                'url'         => UrlDto::fromUri($request->getUri())->toArray(false),
                'timestamp'   => round($request->getServerParams()['REQUEST_TIME_FLOAT'] * 1000),
            ]
        );
    }

    /**
     * @param $headers
     *
     * @return string
     */
    private function extractHeaders($headers)
    {
        $result = '';
        if (!is_array($headers)) {
            return $result;
        }

        foreach ($headers as $name => $values) {
            if ($name === 'cookie') {
                continue;
            }
            $result .= sprintf("%s: %s \n ", $name, implode('', $values));
        }

        return $result;
    }

    /**
     * @param $headers
     *
     * @return string
     */
    private function extractCookies($headers)
    {
        $result = '';
        if (!isset($headers['cookie']) || !is_array($headers['cookie'])) {
            return $result;
        }

        foreach ($headers as $name => $values) {
            if ($name !== 'cookie') {
                continue;
            }
            $result .= sprintf("%s: %s \n ", $name, implode('', $values));
        }

        return $result;
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     */
    private function getCliRequest()
    {
        $serverParams = $this->getRequest()->getServerParams();
        $command      = implode(' ', $serverParams['argv']);
        $timestamp    = round(microtime(true) * 1000);
        return [
            'command'   => $command,
            'timestamp' => $timestamp,
        ];
    }
}
