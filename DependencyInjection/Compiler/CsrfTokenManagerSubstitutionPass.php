<?php

namespace Flying\Bundle\DebugBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
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
        $csrfProvider = 'form.csrf_provider';
        if (!$container->hasDefinition($csrfProvider)) {
            return;
        }
        // CSRF token manager should be used for Symfony 2.4+,
        // CSRF provider - for older versions
        $services = array(
            'provider' => 'debug.csrf.csrf_provider',
            'manager'  => 'debug.csrf.token_manager',
        );
        $type = ($container->hasDefinition('security.csrf.token_manager')) ? 'manager' : 'provider';
        $service = $services[$type];
        unset($services[$type]);
        foreach ($services as $id) {
            $container->removeDefinition($id);
        }
        $definition = $container->getDefinition($service);
        $definition->addMethodCall('setEnabled', array($enabled));
        $definition->addMethodCall('setTokenValidationStatus', array($config['token_validation_status']));
        $provider = $container->getDefinition($csrfProvider);
        $provider->setPublic(false);
        $id = 'debug.csrf.real_form_csrf_provider';
        $container->setDefinition($id, $provider);
        $definition->replaceArgument(0, new Reference($id));
        $container->removeDefinition($service);
        $container->setDefinition($csrfProvider, $definition);
    }
}
