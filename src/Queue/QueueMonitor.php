<?php

namespace Glooby\TaskBundle\Queue;

use Doctrine\Common\Persistence\ManagerRegistry;
use Glooby\TaskBundle\Manager\TaskManager;

/**
 * @author Emil Kilhage
 */
class QueueMonitor
{
    /**
     * @var ManagerRegistry
     */
    protected $doctrine;

    /**
     * @param ManagerRegistry $doctrine
     */
    public function setDoctrine($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @var TaskManager
     */
    private $taskManager;

    /**
     * @param TaskManager $taskManager
     */
    public function setTaskManager(TaskManager $taskManager)
    {
        $this->taskManager = $taskManager;
    }

    /**
     *
     */
    public function monitor()
    {
        $taskRepo = $this->doctrine->getManager()
            ->getRepository('GloobyTaskBundle:QueuedTask');

        foreach ($taskRepo->findRunning() as $task) {
            if (false === posix_getpgid($task->getPId())) {
                $this->taskManager->failure($task, 'crashed');
            }
        }

        $this->doctrine->getManager()->flush();
    }
}
