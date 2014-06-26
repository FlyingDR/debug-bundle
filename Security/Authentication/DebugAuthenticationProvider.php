<?php

namespace Flying\Bundle\DebugBundle\Security\Authentication;

use Flying\Bundle\DebugBundle\Security\Authentication\TokenBuilder\TokenBuilderInterface;
use Flying\Bundle\DebugBundle\Security\Authentication\TokenBuilder\TokenBuilderReceiverInterface;
use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class DebugAuthenticationProvider implements AuthenticationProviderInterface, TokenBuilderReceiverInterface
{
    /**
     * @var boolean
     */
    protected $enabled = true;
    /**
     * @var TokenBuilderInterface
     */
    protected $builder;

    /**
     * {@inheritdoc}
     */
    public function setTokenBuilder(TokenBuilderInterface $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @param boolean $status
     */
    public function setEnabled($status)
    {
        $this->enabled = (boolean)$status;
    }

    /**
     * {@inheritdoc}
     */
    public function authenticate(TokenInterface $token)
    {
        if (!$this->enabled) {
            return null;
        }
        $aToken = $this->builder->buildAuthenticated($token);
        if (!$aToken instanceof TokenInterface) {
            throw new AuthenticationException('Debug authentication failed');
        }
        return $aToken;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(TokenInterface $token)
    {
        return ($token instanceof TokenInterface);
    }
}
