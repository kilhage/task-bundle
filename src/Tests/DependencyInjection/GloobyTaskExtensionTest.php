<?php

namespace Glooby\TaskBundle\Tests\DependencyInjection;

use Glooby\TaskBundle\DependencyInjection\GloobyTaskExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Yaml;

/**
 * @author Emil Kilhage
 */
class GloobyTaskExtensionTest extends TestCase
{
    public function testUnConfigured()
    {
        $config = Yaml::parse(file_get_contents(__DIR__.'/fixtures/config.yml'));

        $containerBuilder = new ContainerBuilder();
        $containerBuilder->setParameter('kernel.debug', true);

        $extension = new GloobyTaskExtension();

        $extension->load($config, $containerBuilder);
    }
}
