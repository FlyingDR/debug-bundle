<?php

namespace Flying\Bundle\DebugBundle\Security\Firewall;

use Flying\Bundle\DebugBundle\DebuggerDetector\Subscriber\DebuggerStatusSubscriberInterface;
use Flying\Bundle\DebugBundle\Security\Authentication\DebugAuthenticationProvider;
use Flying\Bundle\DebugBundle\Security\Authentication\TokenBuilder\TokenBuilderInterface;
use Flying\Bundle\DebugBundle\Security\Authentication\TokenBuilder\TokenBuilderReceiverInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;

abstract class AbstractDebugAuthenticationListener implements ListenerInterface,
    TokenBuilderReceiverInterface, DebuggerStatusSubscriberInterface
{
    /**
     * @var TokenBuilderInterface
     */
    private $tokenBuilder;
    /**
     * @var boolean
     */
    private $enabled;
    /**
     * @var boolean
     */
    private $permanent;
    /**
     * @var boolean
     */
    private $debuggerStatus;
    /**
     * @var SecurityContextInterface
     */
    private $securityContext;
    /**
     * @var AuthenticationManagerInterface
     */
    private $authenticationManager;
    /**
     * @var DebugAuthenticationProvider
     */
    private $debugAuthProvider;

    /**
     * Constructor
     *
     * @param SecurityContextInterface $securityContext
     * @param AuthenticationManagerInterface $authenticationManager
     * @param DebugAuthenticationProvider $debugAuthProvider
     */
    public function __construct(SecurityContextInterface $securityContext, AuthenticationManagerInterface $authenticationManager, DebugAuthenticationProvider $debugAuthProvider)
    {
        $this->securityContext = $securityContext;
        $this->authenticationManager = $authenticationManager;
        $this->debugAuthProvider = $debugAuthProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function setDebuggerStatus($status)
    {
        $this->debuggerStatus = (boolean)$status;
    }

    /**
     * @return boolean
     */
    public function getDebuggerStatus()
    {
        return $this->debuggerStatus;
    }

    /**
     * {@inheritdoc}
     */
    public function setTokenBuilder(TokenBuilderInterface $builder)
    {
        $this->tokenBuilder = $builder;
    }

    /**
     * @return TokenBuilderInterface
     */
    public function getTokenBuilder()
    {
        return $this->tokenBuilder;
    }

    /**
     * @param boolean $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = (boolean)$enabled;
    }

    /**
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param boolean $permanent
     */
    public function setPermanent($permanent)
    {
        $this->permanent = (boolean)$permanent;
    }

    /**
     * @return boolean
     */
    public function getPermanent()
    {
        return $this->permanent;
    }

    /**
     * @return SecurityContextInterface
     */
    public function getSecurityContext()
    {
        return $this->securityContext;
    }

    /**
     * @return AuthenticationManagerInterface
     */
    public function getAuthenticationManager()
    {
        return $this->authenticationManager;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(GetResponseEvent $event)
    {
        $enabled = (($event->getRequestType() === HttpKernelInterface::MASTER_REQUEST) && $this->getEnabled());
        if (!$this->getPermanent()) {
            $enabled &= $this->getDebuggerStatus();
        }
        if (!$enabled) {
            $this->debugAuthProvider->setEnabled(false);
            return;
        }
        $this->doHandle($event);
    }

    abstract protected function doHandle(GetResponseEvent $event);
}
