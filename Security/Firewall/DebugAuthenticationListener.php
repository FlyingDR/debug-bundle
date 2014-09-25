<?php

namespace Flying\Bundle\DebugBundle\Security\Firewall;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class DebugAuthenticationListener extends AbstractDebugAuthenticationListener
{
    /**
     * {@inheritdoc}
     */
    protected function doHandle(GetResponseEvent $event)
    {
        $token = $this->getSecurityContext()->getToken();
        if (($token instanceof TokenInterface) && ($token->isAuthenticated())) {
            return;
        }
        $token = $this->getTokenBuilder()->build($event->getRequest());
        if ($token instanceof TokenInterface) {
            $authenticatedToken = $this->getAuthenticationManager()->authenticate($token);
            $this->getSecurityContext()->setToken($authenticatedToken);
        }
    }
}
