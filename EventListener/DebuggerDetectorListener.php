<?php

namespace Flying\Bundle\DebugCsrfBundle\EventListener;

use Flying\Bundle\DebugCsrfBundle\Csrf\DebugCsrfTokenManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class DebuggerDetectorListener implements EventSubscriberInterface
{
    /**
     * @var boolean
     */
    protected $enabled = false;
    /**
     * @var DebugCsrfTokenManager
     */
    protected $debugTokenManager;
    /**
     * @var boolean
     */
    protected $underDebugger;

    /**
     * @param boolean $enabled
     * @param DebugCsrfTokenManager $debugTokenManager
     */
    public function __construct($enabled, DebugCsrfTokenManager $debugTokenManager)
    {
        $this->enabled = $enabled;
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
        $this->detectDebugger($event->getRequest());
        $this->debugTokenManager->setEnabled($this->isUnderDebugger());
    }

    /**
     * Detect if given request is running under debugger
     *
     * @param Request $request
     * @return void
     */
    protected function detectDebugger(Request $request)
    {
        $this->underDebugger = false;
        if (!$this->enabled) {
            return;
        }
        if ((extension_loaded('Xdebug')) &&
            ($request->query->has('XDEBUG_SESSION_START'))
        ) {
            $this->underDebugger = true;
        } elseif ((extension_loaded('Zend Debugger')) &&
            ($request->query->has('start_debug')) &&
            ($request->query->has('original_url'))
        ) {
            $this->underDebugger = true;
        }
    }

    /**
     * Check if current request is running under debugger
     *
     * @return boolean
     */
    public function isUnderDebugger()
    {
        return (boolean)$this->underDebugger;
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
