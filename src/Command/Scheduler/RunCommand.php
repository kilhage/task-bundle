<?php

namespace Glooby\TaskBundle\Command\Scheduler;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Emil Kilhage
 */
class RunCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('scheduler:run');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $runner = $this->getContainer()->get('glooby_task.queue_processor');
        $runner->setOutput($output);
        $runner->process();

        $scheduler = $this->getContainer()->get('glooby_task.queue_scheduler');
        $scheduler->schedule();
    }
}
