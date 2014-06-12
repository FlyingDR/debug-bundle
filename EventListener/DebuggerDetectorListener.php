<?php

namespace Flying\Bundle\DebugCsrfBundle\EventListener;

use Flying\Bundle\DebugCsrfBundle\Csrf\DebugCsrfTokenManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class DebuggerDetectorListener implements EventSubscriberInterface
{
    /**
     * @var DebugCsrfTokenManager
     */
    protected $debugTokenManager;
    /**
     * @var boolean
     */
    protected $enabled;

    /**
     * @param DebugCsrfTokenManager $debugTokenManager
     */
    public function __construct(DebugCsrfTokenManager $debugTokenManager)
    {
        $this->debugTokenManager = $debugTokenManager;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
            return;
        }
        if ($this->enabled === null) {
            $this->enabled = false;
            $request = $event->getRequest();
            if ((extension_loaded('Xdebug')) &&
                ($request->query->has('XDEBUG_SESSION_START'))
            ) {
                $this->enabled = true;
            } elseif ((extension_loaded('Zend Debugger')) &&
                ($request->query->has('start_debug')) &&
                ($request->query->has('original_url'))
            ) {
                $this->enabled = true;
            }
        }
        $this->debugTokenManager->setEnabled($this->enabled);
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
