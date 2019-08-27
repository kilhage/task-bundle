<?php

namespace Glooby\TaskBundle\Tests\Schedule;

use Doctrine\Common\Annotations\Reader;
use Glooby\TaskBundle\Annotation\Schedule;
use Glooby\TaskBundle\Schedule\ScheduleRegistry;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author Emil Kilhage
 */
class ScheduleRegistryTest extends TestCase
{
    public function testOne()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $reader = $this->prophesize(Reader::class);

        $registry = new ScheduleRegistry();
        $registry->setContainer($container->reveal());
        $registry->setReader($reader->reveal());

        $registry->addTask('task.foo.a');
        $registry->addTask('task.foo.b');

        $container->get('task.foo.a')->willReturn(new \stdClass());
        $container->get('task.foo.b')->willReturn(new \stdClass());

        $schedule1 = new Schedule(['value' => '@daily']);

        $reader->getClassAnnotation(Argument::type(\ReflectionObject::class), ScheduleRegistry::ANNOTATION_CLASS)
            ->shouldBeCalled()
            ->willReturn($schedule1);

        $schedules = $registry->getSchedules();

        $this->assertTrue(isset($schedules['task.foo.a']));
        $this->assertSame($schedule1, $schedules['task.foo.a']);

        $this->assertTrue(isset($schedules['task.foo.b']));
        $this->assertSame($schedule1, $schedules['task.foo.b']);
    }

    public function testEmpty()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $reader = $this->prophesize(Reader::class);

        $registry = new ScheduleRegistry();
        $registry->setContainer($container->reveal());
        $registry->setReader($reader->reveal());

        $reader->getClassAnnotation(Argument::type(\ReflectionObject::class), ScheduleRegistry::ANNOTATION_CLASS)
            ->shouldNotBeCalled()
            ->willReturn(null);

        $schedules = $registry->getSchedules();

        $this->assertEmpty($schedules);
    }

    public function testMissingScheduleAnnotation()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $reader = $this->prophesize(Reader::class);

        $registry = new ScheduleRegistry();
        $registry->setContainer($container->reveal());
        $registry->setReader($reader->reveal());

        $registry->addTask('task.foo.a');
        $registry->addTask('task.foo.b');

        $container->get('task.foo.a')->willReturn(new \stdClass());
        $container->get('task.foo.b')->willReturn(new \stdClass());

        $reader->getClassAnnotation(Argument::type(\ReflectionObject::class), ScheduleRegistry::ANNOTATION_CLASS)
            ->shouldBeCalled()
            ->willReturn(null);

        $this->expectException('\InvalidArgumentException');

        $registry->getSchedules();
    }
}
