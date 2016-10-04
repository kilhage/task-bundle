<?php

namespace Glooby\TaskBundle\Command\Task;

use Doctrine\ORM\NoResultException;
use Glooby\TaskBundle\Task\TaskRunner;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Emil Kilhage
 */
class RunCommand extends ContainerAwareCommand
{
    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setName('task:run');
        $this->addArgument('service', InputArgument::OPTIONAL);
        $this->addOption('silent', 'S', InputOption::VALUE_NONE);
        $this->addOption('id', null, InputOption::VALUE_REQUIRED);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $runner = $this->getContainer()->get('glooby_task.task_runner');
        $runner->setOutput($output);

        if ($input->getOption('id')) {
            $this->runId($input, $runner);
        } else {
            $response = $runner->runTask($input->getArgument('service'));

            if (!$input->getOption('silent')) {
                $output->writeln($response);
            }
        }
    }

    /**
     * @param InputInterface $input
     * @param TaskRunner $runner
     * @throws NoResultException
     */
    protected function runId(InputInterface $input, TaskRunner $runner)
    {
        $task = $this->getContainer()
            ->get('doctrine')
            ->getManager()
            ->getRepository('GloobyTaskBundle:QueuedTask')
            ->find($input->getOption('id'));

        if (null === $task) {
            throw new NoResultException();
        }

        $runner->run($task);
    }
}
