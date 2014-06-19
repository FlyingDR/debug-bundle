<?php

namespace Flying\Bundle\DebugBundle\Security\Authentication\TokenBuilder;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

abstract class AbstractTokenBuilder implements TokenBuilderInterface
{
    /**
     * @var mixed
     */
    protected $config;

    /**
     * {@inheritdoc}
     */
    public function setTokenConfig($config)
    {
        $this->config = $config;
    }

    /**
     * Get token builder configuration
     *
     * @return mixed
     */
    public function getTokenConfig()
    {
        return $this->config;
    }

    /**
     * {@inheritdoc}
     */
    public function buildAuthenticated(TokenInterface $token)
    {
        return clone $token;
    }
}
