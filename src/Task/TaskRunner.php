<?php

namespace Glooby\TaskBundle\Task;

use Glooby\TaskBundle\Entity\QueuedTask;
use Glooby\TaskBundle\Manager\TaskManager;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * @author Emil Kilhage
 */
class TaskRunner
{
    use ContainerAwareTrait;
    use LoggerAwareTrait;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @param OutputInterface $output
     */
    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;
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
     * @param QueuedTask $queuedTask
     * @return array
     * @throws \Exception
     */
    public function run(QueuedTask $queuedTask)
    {
        $task = $this->container->get($queuedTask->getName());

        if (!($task instanceof TaskInterface)) {
            throw new \InvalidArgumentException($queuedTask->getName().' does not implement TaskInterface');
        }

        $this->taskManager->start($queuedTask);

        try {
            if ($queuedTask->hasParams()) {
                $response = $task->run($queuedTask->getParams());
            } else {
                $response = $task->run();
            }

            $this->logger->debug("$response");
            $this->taskManager->success($queuedTask, $response);
        } catch (\Exception $e) {
            $this->logger->error("$e");
            $this->taskManager->failure($queuedTask, "$e");
            throw $e;
        }

        return $response;
    }

    /**
     * @param string $name
     * @param array $params
     * @return array
     * @throws \Exception
     */
    public function runTask($name, array $params = [])
    {
        $task = $this->container->get($name);

        if (!($task instanceof TaskInterface)) {
            throw new \InvalidArgumentException($name.' does not implement TaskInterface');
        }

        $run = $this->taskManager->run($name, $params);

        try {
            if (count($params) > 0) {
                $response = $task->run($params);
            } else {
                $response = $task->run();
            }

            $this->taskManager->success($run, $response);
        } catch (\Exception $e) {
            $this->logger->error("$e");
            $this->taskManager->failure($run, "$e");
            throw $e;
        }

        return $response;
    }
}
