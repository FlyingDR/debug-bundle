<?php

namespace Flying\Bundle\DebugBundle\Security\Factory;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Exception\LogicException;

class DebugAuthenticationFactory implements SecurityFactoryInterface
{
    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
    {
        $builder = $config['token_builder'];
        if (class_exists($builder)) {
            // Token builder is defined as FQCN, convert it into service
            $r = new \ReflectionClass($builder);
            if (!$r->implementsInterface('Flying\Bundle\DebugBundle\Security\Authentication\TokenBuilder\TokenBuilderInterface')) {
                throw new LogicException('Token builder should implement TokenBuilderInterface interface: ' . $builder);
            }
            $builderId = join('.', array($this->getKey(), 'token_builder', uniqid('tb', true)));
            $builder = new Definition($builder);
            $builder->setPublic(false);
            $container->setDefinition($builderId, $builder);
            $config['token_builder'] = $builderId;
        }
        $container->setParameter('debug.security.config', $config);
        return array($config['auth_provider'], $config['auth_listener'], null);
    }

    public function getPosition()
    {
        return 'pre_auth';
    }

    public function getKey()
    {
        return 'debug';
    }

    public function addConfiguration(NodeDefinition $builder)
    {
        // @formatter:off
        /** @noinspection PhpUndefinedMethodInspection */
        $builder
            ->children()
                ->booleanNode('enabled')->defaultValue('%kernel.debug%')->end()
                ->booleanNode('permanent')->defaultFalse()->end()
                ->scalarNode('token_builder')->isRequired()->end()
                ->arrayNode('token_config')->prototype('scalar')->end()->end()
                ->scalarNode('auth_provider')->defaultValue('debug.security.auth_provider')->end()
                ->scalarNode('auth_listener')->defaultValue('debug.security.auth_listener')->end()
            ->end()
        ;
        // @formatter:on
    }
}
