<?php

namespace Glooby\TaskBundle\Schedule;

use Glooby\TaskBundle\Annotation\Schedule;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * @author Emil Kilhage
 */
class ScheduleRegistry
{
    const ANNOTATION_CLASS = Schedule::class;

    use ContainerAwareTrait;

    /**
     * @var array
     */
    private $tasks = [];

    /**
     * @var Reader
     */
    private $reader;

    /**
     * @param Reader $reader
     */
    public function setReader(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @param string $id
     */
    public function addTask($id)
    {
        $this->tasks[] = $id;
    }

    /**
     * @return Schedule[]
     */
    public function getSchedules()
    {
        $schedules = [];

        foreach ($this->tasks as $taskId) {
            $schedules[$taskId] = $this->getAnnotation($taskId);
        }

        return $schedules;
    }

    /**
     * @param string $taskId
     *
     * @return array
     */
    private function getAnnotation($taskId)
    {
        $task = $this->container->get($taskId);

        $reflectionObject = new \ReflectionObject($task);
        $annotation = $this->reader->getClassAnnotation($reflectionObject, self::ANNOTATION_CLASS);

        $this->guardAgainstInvalidAnnotation($annotation, $task);

        return $annotation;
    }

    /**
     * @param $annotation
     * @param $task
     */
    private function guardAgainstInvalidAnnotation($annotation, $task)
    {
        if (!is_a($annotation, self::ANNOTATION_CLASS)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'class %s is missing the Schedule annotation %s',
                    get_class($task),
                    self::ANNOTATION_CLASS
                )
            );
        }
    }
}
