<?php

namespace Glooby\TaskBundle\Command\Scheduler;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Emil Kilhage
 */
class PruneCommand extends ContainerAwareCommand
{
    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setName('scheduler:prune');
        $this->addOption('all', 'A', InputOption::VALUE_NONE);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $pruner = $this->getContainer()->get('glooby_task.queue_pruner');

        if ($input->getOption('all')) {
            $pruner->all();
        } else {
            $pruner->run();
        }
    }
}
