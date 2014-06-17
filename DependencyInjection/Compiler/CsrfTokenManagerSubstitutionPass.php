<?php

namespace Flying\Bundle\DebugBundle\DependencyInjection\Compiler;

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
        $config = $container->getParameter('debug.csrf.config');
        $enabled = (($config['enabled']) && ($container->getParameter('kernel.debug')));
        $csrfExtension = 'form.type_extension.csrf';
        if (!$container->hasDefinition($csrfExtension)) {
            return;
        }
        $realManager = $config['real_token_manager'];
        if (!$container->hasDefinition($realManager)) {
            throw new InvalidArgumentException('Unavailable CSRF token manager service: ' . $realManager);
        }
        $debugManagerId = 'debug.csrf.debug_token_manager';
        $debugManager = $container->getDefinition($debugManagerId);
        $debugManager->replaceArgument(0, new Reference($realManager));
        $debugManager->addMethodCall('setEnabled', array($enabled));
        $debugManager->addMethodCall('setTokenValidationStatus', array($config['token_validation_status']));
        $container->getDefinition($csrfExtension)->replaceArgument(0, new Reference($debugManagerId));
    }
}
