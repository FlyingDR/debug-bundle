<?php

namespace Flying\Bundle\DebugCsrfBundle\Csrf;

use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class DebugCsrfTokenManager implements CsrfTokenManagerInterface
{
    /**
     * @var boolean
     */
    private $enabled = true;
    /**
     * @var mixed
     */
    private $status = true;
    /**
     * @var CsrfTokenManagerInterface
     */
    private $realTokenManager;

    /**
     * @param CsrfTokenManagerInterface $tokenManager
     * @param mixed $tokenValidationStatus
     */
    public function __construct(CsrfTokenManagerInterface $tokenManager, $tokenValidationStatus = true)
    {
        $this->realTokenManager = $tokenManager;
        $this->setStatus($tokenValidationStatus);
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
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
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
            return $this->getStatus();
        }
        return $this->realTokenManager->isTokenValid($token);
    }
}
