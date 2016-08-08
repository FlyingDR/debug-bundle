<?php

namespace Flying\Bundle\DebugBundle\DebuggerDetector\EventListener;

use Flying\Bundle\DebugBundle\DebuggerDetector\Subscriber\DebuggerStatusSubscriberInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class DebuggerDetectorListener implements EventSubscriberInterface
{
    /**
     * @var DebuggerStatusSubscriberInterface[]
     */
    protected $subscribers;

    public function __construct()
    {
        $this->subscribers = array();
    }

    /**
     * @param DebuggerStatusSubscriberInterface $subscriber
     */
    public function addSubscriber(DebuggerStatusSubscriberInterface $subscriber)
    {
        $this->subscribers[] = $subscriber;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
            return;
        }
        $status = $this->detectDebugger($event->getRequest());
        foreach ($this->subscribers as $subscriber) {
            $subscriber->setDebuggerStatus($status);
        }
    }

    /**
     * Detect if given request is running under debugger
     *
     * @param Request $request
     * @return boolean
     */
    protected function detectDebugger(Request $request)
    {
        $status = false;
        if (extension_loaded('Xdebug') && $request->query->has('XDEBUG_SESSION_START')) {
            $status = true;
        } elseif (extension_loaded('Zend Debugger') && $request->query->has('start_debug') && $request->query->has('original_url')) {
            $status = true;
        }
        return $status;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array('onKernelRequest', 50),
        );
    }
}
