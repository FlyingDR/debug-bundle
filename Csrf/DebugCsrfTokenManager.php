<?php

namespace Flying\Bundle\DebugBundle\Csrf;

use Flying\Bundle\DebugBundle\DebuggerDetector\Subscriber\DebuggerStatusSubscriberInterface;
use Symfony\Component\Form\Extension\Csrf\CsrfProvider\CsrfProviderAdapter;
use Symfony\Component\Form\Extension\Csrf\CsrfProvider\CsrfProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class DebugCsrfTokenManager extends DebugCsrfStubImplementation implements CsrfTokenManagerInterface, DebuggerStatusSubscriberInterface
{
    /**
     * @var CsrfTokenManagerInterface
     */
    private $realTokenManager;

    /**
     * @param CsrfProviderInterface $csrfProvider
     */
    public function __construct(CsrfProviderInterface $csrfProvider)
    {
        $this->realTokenManager = new CsrfProviderAdapter($csrfProvider);
    }

    /**
     * {@inheritdoc}
     */
    public function getToken($tokenId)
    {
        return $this->realTokenManager->getToken($tokenId);
    }

    /**
     * {@inheritdoc}
     */
    public function refreshToken($tokenId)
    {
        return $this->realTokenManager->refreshToken($tokenId);
    }

    /**
     * {@inheritdoc}
     */
    public function removeToken($tokenId)
    {
        return $this->realTokenManager->removeToken($tokenId);
    }

    /**
     * {@inheritdoc}
     */
    public function isTokenValid(CsrfToken $token)
    {
        if ($this->getEnabled()) {
            return $this->getTokenValidationStatus();
        }
        return $this->realTokenManager->isTokenValid($token);
    }
}
