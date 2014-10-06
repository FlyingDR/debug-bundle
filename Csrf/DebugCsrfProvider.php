<?php

namespace Flying\Bundle\DebugBundle\Csrf;

use Symfony\Component\Form\Extension\Csrf\CsrfProvider\CsrfProviderInterface;

class DebugCsrfProvider extends DebugCsrfStubImplementation implements CsrfProviderInterface
{
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
}
