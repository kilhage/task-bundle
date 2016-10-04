<?php

namespace Glooby\TaskBundle;

use Glooby\TaskBundle\DependencyInjection\Compiler\RegisterSchedulesPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * {@inheritdoc}
 */
class GloobyTaskBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new RegisterSchedulesPass());
    }
}
