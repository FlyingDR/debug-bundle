<?php

namespace Flying\Bundle\DebugBundle;

use Flying\Bundle\DebugBundle\DependencyInjection\Compiler\CsrfTokenManagerSubstitutionPass;
use Flying\Bundle\DebugBundle\DependencyInjection\Compiler\RegisterDebuggerStatusSubscribersPass;
use Flying\Bundle\DebugBundle\DependencyInjection\Compiler\SecurityServicesConfigurationPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DebugBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new SecurityServicesConfigurationPass());
        $container->addCompilerPass(new CsrfTokenManagerSubstitutionPass());
        $container->addCompilerPass(new RegisterDebuggerStatusSubscribersPass());
    }
}
