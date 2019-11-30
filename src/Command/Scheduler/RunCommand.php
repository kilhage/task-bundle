<?php

namespace Glooby\TaskBundle\Command\Scheduler;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author Emil Kilhage
 */
class RunCommand extends Command
{
    private $container;

    public function __construct(ContainerInterface $container){
        parent::__construct();
        $this->container = $container;
    }

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
        $runner = $this->container->get('glooby_task.queue_processor');
        $runner->setOutput($output);
        $runner->process();

        $monitor = $this->container->get('glooby_task.queue_monitor');
        $monitor->monitor();

        $scheduler = $this->container->get('glooby_task.queue_scheduler');
        $scheduler->schedule();
    }
}
