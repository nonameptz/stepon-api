<?php

namespace Application\Util;

/**
 * Class IPUtils
 *
 * @package Application\Util
 */
class IPUtils
{
    /**
     * @return string|null
     */
    public static function extractClientIp()
    {
        switch (true) {
            case !empty($_SERVER['HTTP_X_REAL_IP']) && self::isValid($_SERVER['HTTP_X_REAL_IP']):
                return $_SERVER['HTTP_X_REAL_IP'];
            case !empty($_SERVER['HTTP_X_FORWARDED_FOR']) && self::isValid($_SERVER['HTTP_X_FORWARDED_FOR']):
                return $_SERVER['HTTP_X_FORWARDED_FOR'];
            case !empty($_SERVER['REMOTE_ADDR']) && self::isValid($_SERVER['REMOTE_ADDR']):
                return $_SERVER['REMOTE_ADDR'];
            default:
                return null;
        }
    }

    /**
     * @param string $ip
     *
     * @return bool
     */
    public static function isValid($ip)
    {
        return false !== filter_var($ip, FILTER_VALIDATE_IP);
    }
}
