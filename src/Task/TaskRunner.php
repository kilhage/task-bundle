<?php

namespace Glooby\TaskBundle\Task;

use Glooby\TaskBundle\Entity\QueuedTask;
use Glooby\TaskBundle\Manager\TaskManager;
use Glooby\TaskBundle\Model\QueuedTaskInterface;
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
     * @param QueuedTask $run
     * @return array
     * @throws \Exception
     */
    public function run(QueuedTask $run)
    {
        $task = $this->container->get($run->getName());

        if (!($task instanceof TaskInterface)) {
            throw new \InvalidArgumentException($run->getName().' does not implement TaskInterface');
        }

        $this->taskManager->start($run);

        return $this->execute($task, $run->getParams(), $run);
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

        return $this->execute($task, $params, $run);
    }

    /**
     * @param TaskInterface $task
     * @param array $params
     * @param QueuedTaskInterface $run
     * @return mixed
     * @throws \Exception
     */
    protected function execute(TaskInterface $task, array $params, QueuedTaskInterface $run)
    {
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
