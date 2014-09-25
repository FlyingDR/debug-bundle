<?php

namespace Flying\Bundle\DebugBundle\Security\Authentication\TokenBuilder;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Interface to build debug authentication tokens
 */
interface TokenBuilderInterface
{
    /**
     * Set token builder configuration
     *
     * @param mixed $config
     */
    public function setTokenConfig($config);

    /**
     * Build debug authentication token from given request
     * Return NULL if no token can be built, e.g. if there is
     * no debug user information is available
     *
     * @param Request $request
     * @return TokenInterface|null
     */
    public function build(Request $request);

    /**
     * Build authenticated token based on given token
     * Return NULL to cause authentication failure
     *
     * @param TokenInterface $token
     * @return TokenInterface|null
     */
    public function buildAuthenticated(TokenInterface $token);
}
