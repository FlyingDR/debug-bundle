<?php

namespace Flying\Bundle\DebugCsrfBundle;

use Flying\Bundle\DebugCsrfBundle\DependencyInjection\Compiler\CsrfTokenManagerSubstitutionPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DebugCsrfBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new CsrfTokenManagerSubstitutionPass());
    }
}
