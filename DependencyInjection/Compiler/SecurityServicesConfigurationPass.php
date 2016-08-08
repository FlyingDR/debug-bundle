<?php

namespace Flying\Bundle\DebugBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Reference;

class SecurityServicesConfigurationPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     * @throws InvalidArgumentException
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasParameter('debug.security.config')) {
            return;
        }
        $config = $container->getParameter('debug.security.config');
        $builder = $container->getDefinition($config['token_builder']);
        $builder->addMethodCall('setTokenConfig', array($config['token_config']));
        $provider = $container->getDefinition($config['auth_provider']);
        $provider->addMethodCall('setTokenBuilder', array(new Reference($config['token_builder'])));
        $listener = $container->getDefinition($config['auth_listener']);
        $listener->addMethodCall('setEnabled', array($config['enabled']));
        $listener->addMethodCall('setPermanent', array($config['permanent']));
        $listener->addMethodCall('setTokenBuilder', array(new Reference($config['token_builder'])));
    }
}
