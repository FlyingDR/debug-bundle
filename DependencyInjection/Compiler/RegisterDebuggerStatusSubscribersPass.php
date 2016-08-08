<?php

namespace Flying\Bundle\DebugBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Container compiler pass to register services
 * that want to receive "request is running under debugger" status
 * as soon as it will be determined
 */
class RegisterDebuggerStatusSubscribersPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     * @throws InvalidArgumentException
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('debug.debugger_detector')) {
            return;
        }
        $definition = $container->getDefinition('debug.debugger_detector');
        $subscribers = $container->findTaggedServiceIds('debug.debugger_status.subscriber');
        foreach ($subscribers as $id => $tags) {
            $definition->addMethodCall('addSubscriber', array(new Reference($id)));
        }
    }
}
