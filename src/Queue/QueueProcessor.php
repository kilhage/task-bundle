<?php

namespace Glooby\TaskBundle\Queue;

use Doctrine\Common\Persistence\ManagerRegistry;
use Glooby\TaskBundle\Model\QueuedTaskInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

/**
 * @author Emil Kilhage
 */
class QueueProcessor
{
    /**
     * @var int
     */
    private $limit;

    /**
     * @var bool
     */
    private $debug;

    /**
     * @var Process[]
     */
    private $processes = [];

    /**
     * @var OutputInterface
     */
    protected $output;

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
     * @param OutputInterface $output
     */
    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * @param boolean $debug
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
    }

    /**
     * @param int $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    /**
     * @throws \Exception
     */
    public function process()
    {
        $queueRepo = $this->doctrine->getManager()
            ->getRepository('GloobyTaskBundle:QueuedTask');

        foreach ($queueRepo->findQueued($this->limit) as $queuedTask) {
            $this->start($queuedTask);
        }

        $this->wait();
    }

    /**
     * @return string
     */
    private function getProcessParams()
    {
        $params = [];

        if (!$this->debug) {
            $params[] = '--env=prod';
        }

        return implode(' ', $params);
    }

    /**
     *
     */
    private function wait()
    {
        while (count($this->processes) > 0) {
            sleep(1);

            foreach ($this->processes as $i => $process) {
                if (!$process->isRunning()) {
                    unset($this->processes[$i]);
                    echo $process->getOutput();
                }
            }
        }
    }

    /**
     * @param QueuedTaskInterface $queuedTask
     */
    private function start(QueuedTaskInterface $queuedTask)
    {
        $command = $this->createCommand($queuedTask);
        $process = $this->createProcess($command);

        $this->processes[] = $process;

        if (null !== $this->output) {
            $this->output->writeln("$command");
        }
    }

    /**
     * @param string $command
     * @return Process
     */
    private function createProcess($command)
    {
        $that = $this;
        $nl = false;

        $process = new Process($command);
        $process->setTimeout(0);
        $process->start(function ($type, $data) use ($that, &$nl) {
            if (null !== $that->output) {
                if ($nl) {
                    $nl = false;
                    $that->output->write("\n");
                }

                $that->output->write($data);
            }
        });

        return $process;
    }

    /**
     * @param QueuedTaskInterface $queuedTask
     * @return string
     */
    private function createCommand(QueuedTaskInterface $queuedTask)
    {
        $command = sprintf(
            'php -d memory_limit=%s bin/console task:run --id=%s %s',
            ini_get('memory_limit'),
            $queuedTask->getId(),
            $this->getProcessParams()
        );
        return $command;
    }
}
