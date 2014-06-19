<?php

namespace Flying\Bundle\DebugBundle\Security\Authentication\TokenBuilder;

interface TokenBuilderReceiverInterface
{
    /**
     * Set token builder to use
     *
     * @param TokenBuilderInterface $builder
     */
    public function setTokenBuilder(TokenBuilderInterface $builder);
}
