<?php

namespace Flying\Bundle\DebugCsrfBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Reference;

class CsrfTokenManagerSubstitutionPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $config = $container->getParameter('debug_csrf.config');
        if ((!$config['enabled']) || (!$container->getParameter('kernel.debug'))) {
            return;
        }
        $csrfExtension = 'form.type_extension.csrf';
        if (!$container->hasDefinition($csrfExtension)) {
            return;
        }
        $realManager = $config['real_token_manager'];
        if (!$container->hasDefinition($realManager)) {
            throw new InvalidArgumentException('Unavailable CSRF token manager service: ' . $realManager);
        }
        $csrfExtension = $container->getDefinition($csrfExtension);
        $debugManager = $container->getDefinition('debug_csrf.debug_token_manager');
        $debugManager->setArguments(array(new Reference($realManager), $config['token_validation_status']));
        $csrfExtension->replaceArgument(0, $debugManager);
    }
}
