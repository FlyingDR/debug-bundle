<?php

namespace Flying\Bundle\DebugBundle\Csrf;

use Flying\Bundle\DebugBundle\DebuggerDetector\Subscriber\DebuggerStatusSubscriberInterface;

abstract class DebugCsrfStubImplementation implements DebuggerStatusSubscriberInterface
{
    /**
     * @var boolean
     */
    private $enabled = true;
    /**
     * @var boolean
     */
    private $permanent = false;
    /**
     * @var mixed
     */
    private $tokenValidationStatus = true;

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
     * @return boolean
     */
    public function isPermanent()
    {
        return $this->permanent;
    }

    /**
     * @param boolean $permanent
     */
    public function setPermanent($permanent)
    {
        $this->permanent = (boolean)$permanent;
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
    public function setDebuggerStatus($status)
    {
        if ((!$status) && (!$this->isPermanent())) {
            $this->setEnabled(false);
        }
    }
}
