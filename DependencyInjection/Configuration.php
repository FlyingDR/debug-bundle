<?php

namespace Flying\Bundle\DebugBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $tb = new TreeBuilder();
        $root = $tb->root('debug');
        /** @noinspection PhpUndefinedMethodInspection */
        // @formatter:off
        $root
            ->children()
                ->booleanNode('enabled')
                    ->defaultTrue()
                ->end()
                ->scalarNode('real_token_manager')
                    ->defaultValue('security.csrf.token_manager')
                ->end()
                ->booleanNode('token_validation_status')
                    ->defaultTrue()
                ->end()
            ->end();
        // @formatter:on

        return $tb;
    }
}
