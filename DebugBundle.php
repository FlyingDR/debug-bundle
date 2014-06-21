<?php

namespace Flying\Bundle\DebugBundle;

use Flying\Bundle\DebugBundle\DependencyInjection\Compiler\CsrfTokenManagerSubstitutionPass;
use Flying\Bundle\DebugBundle\DependencyInjection\Compiler\RegisterDebuggerStatusSubscribersPass;
use Flying\Bundle\DebugBundle\DependencyInjection\Compiler\SecurityServicesConfigurationPass;
use Flying\Bundle\DebugBundle\Security\Factory\DebugAuthenticationFactory;
use Symfony\Bundle\SecurityBundle\DependencyInjection\SecurityExtension;
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
        /** @var $security SecurityExtension */
        $security = $container->getExtension('security');
        $security->addSecurityListenerFactory(new DebugAuthenticationFactory());
    }
}
