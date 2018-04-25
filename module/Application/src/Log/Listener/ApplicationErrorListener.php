<?php
/**
 * File contains Class ApplicationErrorListener
 *
 * @since  06.07.2017
 * @author Anton Shepotko <anton.shepotko@veeam.com>
 */

namespace Application\Log\Listener;

use Util\Logger\FatalTracker;
use Util\Logger\PPLog;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\Application;
use Zend\Mvc\MvcEvent;
use ZfcRbac\Exception\UnauthorizedException;

/**
 * Class ApplicationErrorListener
 *
 * @package Application\Log\Listener
 * @author  Anton Shepotko <anton.shepotko@veeam.com>
 */
class ApplicationErrorListener extends AbstractListenerAggregate
{
    /**
     * {@inheritdoc}
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->detach($events);
        $this->listeners[] = $events->attach(
            MvcEvent::EVENT_RENDER_ERROR,
            [$this, 'onError'],
            999
        );
        $this->listeners[] = $events->attach(
            MvcEvent::EVENT_DISPATCH_ERROR,
            [$this, 'onError'],
            999
        );
    }

    /**
     * @param EventInterface $event
     */
    public function onError(EventInterface $event)
    {
        if (!$event instanceof MvcEvent) {
            return;
        }

        if (false === $event->isError()) {
            return;
        }

        $error = $event->getError();
        switch ($error) {
            case Application::ERROR_CONTROLLER_NOT_FOUND:
            case Application::ERROR_CONTROLLER_INVALID:
            case Application::ERROR_ROUTER_NO_MATCH:
                return;
            case Application::ERROR_EXCEPTION:
            default:
                $exception = $event->getParam('exception');
                if ($exception instanceof UnauthorizedException) {
                    return;
                }

                if (!$exception instanceof \Throwable) {
                    $message   = 'Error code: ' . $error;
                    $exception = new \ErrorException(
                        sprintf('%s: %s', FatalTracker::TYPE_UNHANDLED_EXCEPTION, $message),
                        FatalTracker::CODE_UNHANDLED_EXCEPTION
                    );
                } else {
                    $exception = new \ErrorException(
                        sprintf('%s: %s', FatalTracker::TYPE_UNHANDLED_EXCEPTION, $exception->getMessage()),
                        FatalTracker::CODE_UNHANDLED_EXCEPTION,
                        $exception->getCode(),
                        $exception->getFile(),
                        $exception->getLine(),
                        $exception
                    );
                }

                PPLog::fatal($exception);
        }
    }
}
