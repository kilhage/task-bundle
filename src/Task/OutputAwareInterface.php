<?php

namespace Glooby\TaskBundle\Task;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Emil Kilhage
 */
interface OutputAwareInterface
{
    /**
     * @param OutputInterface $output
     */
    public function setOutput(OutputInterface $output);
}
