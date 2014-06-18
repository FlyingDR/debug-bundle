<?php

namespace Flying\Bundle\DebugBundle\Csrf;

use Flying\Bundle\DebugBundle\DebuggerDetector\Subscriber\DebuggerStatusSubscriberInterface;
use Symfony\Component\Form\Extension\Csrf\CsrfProvider\CsrfProviderInterface;

class DebugCsrfProvider implements CsrfProviderInterface, DebuggerStatusSubscriberInterface
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
     * @var CsrfProviderInterface
     */
    private $csrfProvider;

    /**
     * @param CsrfProviderInterface $csrfProvider
     */
    public function __construct(CsrfProviderInterface $csrfProvider)
    {
        $this->csrfProvider = $csrfProvider;
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
    public function generateCsrfToken($intention)
    {
        return $this->csrfProvider->generateCsrfToken($intention);
    }

    /**
     * {@inheritdoc}
     */
    public function isCsrfTokenValid($intention, $token)
    {
        if ($this->getEnabled()) {
            return $this->getTokenValidationStatus();
        }
        return $this->csrfProvider->isCsrfTokenValid($intention, $token);
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
