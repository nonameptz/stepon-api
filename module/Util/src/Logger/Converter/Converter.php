<?php

namespace Util\Logger\Converter;

use Util\Logger\Event\Event;

/**
 * Class Converter
 *
 * @package Util\Logger\Converter
 */
class Converter
{
    /**
     * @param Event $event
     *
     * @return array
     */
    public static function convert(Event $event)
    {
        $result = [
            'label'     => $event->getLabel(),
            'status'    => $event->getStatus(),
            'timestamp' => $event->getTimestamp(),
        ];

        if (!empty($event->getError())) {
            $result['error'] = self::getJsonPretty($event->getError());
        }

        if (!empty($event->getData())) {
            $result['data'] = self::getJsonPretty($event->getData());
        }

        if (!empty($event->getResponse())) {
            $result['response'] = self::getJsonPretty($event->getResponse());
        }

        if (!empty($event->getSingleValues())) {
            $result = array_merge($result, $event->getSingleValues());
        }

        return $result;
    }

    /**
     * @param array|\stdClass $data
     *
     * @return string
     */
    private static function getJsonPretty($data)
    {
        $data = is_array($data) ? $data : (array)$data;
        $json = json_encode($data, JSON_PRETTY_PRINT);
        return substr($json, 0, 2048);
    }
}
