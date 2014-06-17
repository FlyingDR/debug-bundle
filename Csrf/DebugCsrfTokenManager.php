<?php

namespace Flying\Bundle\DebugBundle\Csrf;

use Flying\Bundle\DebugBundle\DebuggerDetector\Subscriber\DebuggerStatusSubscriberInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class DebugCsrfTokenManager implements CsrfTokenManagerInterface, DebuggerStatusSubscriberInterface
{
    /**
     * @var boolean
     */
    private $enabled = true;
    /**
     * @var mixed
     */
    private $tokenValidationStatus = true;
    /**
     * @var CsrfTokenManagerInterface
     */
    private $realTokenManager;

    /**
     * @param CsrfTokenManagerInterface $tokenManager
     */
    public function __construct(CsrfTokenManagerInterface $tokenManager)
    {
        $this->realTokenManager = $tokenManager;
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
     * @param mixed $status
     */
    public function setTokenValidationStatus($status)
    {
        $this->tokenValidationStatus = $status;
    }

    /**
     * @return mixed
     */
    public function getTokenValidationStatus()
    {
        return $this->tokenValidationStatus;
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

    /**
     * {@inheritdoc}
     */
    public function setDebuggerStatus($status)
    {
        // Disable CSRF validation substitution if request is not running under debugger
        if (!$status) {
            $this->setEnabled(false);
        }
    }
}
