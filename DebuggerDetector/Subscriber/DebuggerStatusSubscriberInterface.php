<?php

namespace Flying\Bundle\DebugBundle\DebuggerDetector\Subscriber;

/**
 * Interface for subscribers for "request is running under debugger" status
 */
interface DebuggerStatusSubscriberInterface
{
    /**
     * Set "request is running under debugger" status
     *
     * @param boolean $status
     */
    public function setDebuggerStatus($status);
}
