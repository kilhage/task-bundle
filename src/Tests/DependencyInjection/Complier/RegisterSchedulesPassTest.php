<?php

namespace Glooby\TaskBundle\Tests\DependencyInjection\Compiler;

use Glooby\TaskBundle\DependencyInjection\Compiler\RegisterSchedulesPass;
use Glooby\TaskBundle\Task\TaskInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Emil Kilhage
 */
class RegisterSchedulesPassTest extends TestCase
{
    public function testAddsTaskIdsToRegistry()
    {
        $container = new ContainerBuilder();
        $pass = new RegisterSchedulesPass();

        $registryDefinition = new Definition();

        $container->setDefinition('glooby_task.schedule_registry', $registryDefinition);

        $container->setDefinition('task.foo.a', $this->createScheduledTaskDefinition());
        $container->setDefinition('task.foo.b', $this->createScheduledTaskDefinition());
        $container->setDefinition('task.bar.a', $this->createScheduledTaskDefinition());
        $container->setDefinition('task.foo.f', $this->createTaskDefinition());

        $pass->process($container);

        $calls = $registryDefinition->getMethodCalls();

        $this->assertCount(3, $calls);

        $this->assertEquals(array('addTask', array('task.foo.a')), $calls[0]);
        $this->assertEquals(array('addTask', array('task.foo.b')), $calls[1]);
        $this->assertEquals(array('addTask', array('task.bar.a')), $calls[2]);
    }

    public function testEmptyClass()
    {
        $container = new ContainerBuilder();
        $pass = new RegisterSchedulesPass();

        $registryDefinition = new Definition();

        $container->setDefinition('glooby_task.schedule_registry', $registryDefinition);

        $definition = new Definition();
        $definition->addTag('glooby.scheduled_task');

        $container->setDefinition('task.foo.f', $definition);

        $pass->process($container);
    }

    public function testInvalidClass()
    {
        $container = new ContainerBuilder();
        $pass = new RegisterSchedulesPass();

        $registryDefinition = new Definition();

        $container->setDefinition('glooby_task.schedule_registry', $registryDefinition);

        $definition = new Definition(self::class);
        $definition->addTag('glooby.scheduled_task');

        $container->setDefinition('task.foo.f', $definition);

        $this->expectException('\InvalidArgumentException');

        $pass->process($container);
    }

    public function testNoTag()
    {
        $container = new ContainerBuilder();
        $pass = new RegisterSchedulesPass();

        $registryDefinition = new Definition();

        $container->setDefinition('glooby_task.schedule_registry', $registryDefinition);

        $container->setDefinition('task.foo.f', new Definition(self::class));
        $container->setDefinition('task.foo.f', new Definition());

        $pass->process($container);

        $calls = $registryDefinition->getMethodCalls();

        $this->assertCount(0, $calls);
    }

    private function createScheduledTaskDefinition(array $attributes = array())
    {
        $definition = $this->createTaskDefinition();
        $definition->addTag('glooby.scheduled_task', $attributes);

        return $definition;
    }

    private function createTaskDefinition()
    {
        $task = $this->prophesize(TaskInterface::class);

        return new Definition(get_class($task->reveal()));
    }
}
