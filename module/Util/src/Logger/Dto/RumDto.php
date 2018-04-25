<?php

namespace Util\Logger\Dto;

/**
 * Class RumDto
 *
 * @package Util\Logger\Dto
 */
class RumDto
{
    /**
     * @var string
     */
    protected $requestType;

    /**
     * @var string
     */
    protected $requestUrl;

    /**
     * @var string
     */
    protected $requestReferrer;

    /**
     * @var string
     */
    protected $requestReferrer2;

    /**
     * @var string
     */
    protected $nextUrl;

    /**
     * @var string
     */
    protected $requestInitiator;

    /**
     * @var int
     */
    protected $requestTime;

    /**
     * @var int
     */
    protected $ttfb;

    /**
     * @var int
     */
    protected $pageDoneTime;

    /**
     * @var int
     */
    protected $bandwidth;

    /**
     * @var int
     */
    protected $latency;

    /**
     * @var array
     */
    protected $browserMetadata;

    /**
     * @param array $rumMeasure
     *
     * @return RumDto
     */
    public static function factory(array $rumMeasure)
    {
        $rumModel = new self();

        if (isset($rumMeasure['rt_start'])) {
            $rumModel->requestType = $rumMeasure['rt_start'] == 'navigation' ? 'location' : 'XHR';
        }

        if (isset($rumMeasure['u'])) {
            $rumModel->requestUrl = (string)$rumMeasure['u'];
        }

        if (isset($rumMeasure['pgu'])) {
            $rumModel->requestInitiator = (string)$rumMeasure['pgu'];
        }

        if (isset($rumMeasure['r'])) {
            $rumModel->requestReferrer = (string)$rumMeasure['r'];
        }

        if (isset($rumMeasure['r2'])) {
            $rumModel->requestReferrer2 = (string)$rumMeasure['r2'];
        }

        if (isset($rumMeasure['nu'])) {
            $rumModel->nextUrl = (string)$rumMeasure['nu'];
        }

        if (isset($rumMeasure['rt_bstart'])) {
            $rumModel->requestTime = (int)$rumMeasure['rt_bstart'];
        }

        if (isset($rumMeasure['t_resp'])) {
            $rumModel->ttfb = (int)$rumMeasure['t_resp'];
        }

        if (isset($rumMeasure['t_done'])) {
            $rumModel->pageDoneTime = (int)$rumMeasure['t_done'];
        }

        if (isset($rumMeasure['bw'])) {
            $rumModel->bandwidth = (int)$rumMeasure['bw'];
        }

        if (isset($rumMeasure['lat'])) {
            $rumModel->latency = (int)$rumMeasure['lat'];
        }

        $browserMetadata = [];
        foreach ($rumMeasure as $name => $value){
            if (strpos($name, 'browser_') === false){
                continue;
            }

            $browserMetadata[$name] = $value;
        }
        $rumModel->browserMetadata = $browserMetadata;

        return $rumModel;
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

    /**
     * @return string
     */
    public function getRequestUrl()
    {
        return $this->requestUrl;
    }
}