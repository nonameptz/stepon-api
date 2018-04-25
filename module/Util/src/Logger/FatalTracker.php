<?php

namespace Util\Logger;

use Throwable;

/**
 * Class FatalTracker
 *
 * @package Util\Logger
 */
class FatalTracker
{
    const TYPE_FATAL               = 'Fatal error';
    const TYPE_UNHANDLED_ERROR     = 'Unhandled error';
    const TYPE_UNHANDLED_EXCEPTION = 'Unhandled exception';

    const CODE_UNHANDLED_EXCEPTION = -1;

    /**
     * Init error handling mechanism
     */
    public static function init()
    {
        new self();
    }

    /**
     * FatalTracker constructor.
     */
    public function __construct()
    {
        error_reporting(E_ALL);
        set_exception_handler([$this, 'logException']);
        set_error_handler([$this, 'catchError']);
        register_shutdown_function([$this, 'catchFatal']);
        //register_shutdown_function([$this, 'registerResponse']);
    }

    /**
     * @param int    $code
     * @param string $message
     * @param string $file
     * @param string $line
     */
    public function catchError($code, $message, $file, $line)
    {
        $this->logError(
            new \ErrorException(
                sprintf('%s: %s', self::TYPE_UNHANDLED_ERROR, $message),
                $code,
                $code,
                $file,
                $line
            )
        );
    }

    /**
     * Handle fatal errors
     */
    public function catchFatal()
    {
        $error = error_get_last();
        if (empty($error) || $error['type'] === E_DEPRECATED) {
            return;
        }

        if (PHP_SAPI !== 'cli') {
            ob_end_clean();
            http_response_code(500);
            if (false === headers_sent()) {
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            }
        }

        $this->logError(
            new \ErrorException(
                sprintf('%s: %s', self::TYPE_FATAL, $error['message']),
                $error['type'],
                $error['type'],
                $error['file'],
                $error['line']
            )
        );
    }

    /**
     * @param Throwable $exception
     */
    public function logException(Throwable $exception)
    {
        $error = new \ErrorException(
            sprintf('%s: %s', self::TYPE_UNHANDLED_EXCEPTION, $exception->getMessage()),
            self::CODE_UNHANDLED_EXCEPTION,
            $exception->getCode(),
            $exception->getFile(),
            $exception->getLine()
        );

        $this->logError($error);
    }

    /**
     * @param \Exception $exception
     */
    public function logError(\Exception $exception)
    {
        PPLog::fatal($exception);
    }

    /**
     * register request
     */
    public function registerResponse(){
        PPLog::request();
    }
}
