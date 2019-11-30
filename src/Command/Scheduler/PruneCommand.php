<?php

namespace Glooby\TaskBundle\Command\Scheduler;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author Emil Kilhage
 */
class PruneCommand extends Command
{
    private $container;

    public function __construct(ContainerInterface $container){
        parent::__construct();
        $this->container = $container;
    }

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
        $pruner =$this->container->get('glooby_task.queue_pruner');

        if ($input->getOption('all')) {
            $pruner->all();
        } else {
            $pruner->run();
        }
    }
}
