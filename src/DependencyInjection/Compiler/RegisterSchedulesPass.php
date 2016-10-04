<?php

namespace Glooby\TaskBundle\DependencyInjection\Compiler;

use Glooby\TaskBundle\Task\TaskInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @author Emil Kilhage
 */
class RegisterSchedulesPass implements CompilerPassInterface
{
    /**
     * Mapping of class names to booleans indicating whether the class
     * implements TaskInterface.
     *
     * @var array
     */
    private $implementations = array();

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $registry = $container->getDefinition('glooby_task.schedule_registry');

        foreach ($container->findTaggedServiceIds('glooby.scheduled_task') as $taskId => $tags) {
            $this->add($container, $taskId, $registry);
        }
    }

    /**
     * Returns whether the class implements TaskInterface.
     *
     * @param string $class
     *
     * @return bool
     */
    private function isTaskImplementation($class)
    {
        if (!isset($this->implementations[$class])) {
            $reflectionClass = new \ReflectionClass($class);
            $this->implementations[$class] = $reflectionClass->implementsInterface(TaskInterface::class);
        }

        return $this->implementations[$class];
    }

    /**
     * @param string $class
     * @param string $taskId
     */
    protected function validateClass($class, $taskId)
    {
        if (!class_exists($class)) {
            throw new \InvalidArgumentException('Invalid class: ' . $class);
        }

        if ($class && !$this->isTaskImplementation($class)) {
            throw new \InvalidArgumentException(sprintf('schedule "%s" with class "%s" must implement TaskInterface.', $taskId, $class));
        }
    }

    /**
     * @param ContainerBuilder $container
     * @param string $taskId
     * @param Definition $registry
     */
    protected function add(ContainerBuilder $container, $taskId, Definition $registry)
    {
        $definition = $container->getDefinition($taskId);
        $class = $definition->getClass();

        $this->validateClass($class, $taskId);

        $registry->addMethodCall('addTask', [$taskId]);
    }
}
