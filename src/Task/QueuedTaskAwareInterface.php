<?php

namespace Glooby\TaskBundle\Task;

use Glooby\TaskBundle\Model\QueuedTaskInterface;

/**
 * @author Emil Kilhage
 */
interface QueuedTaskAwareInterface
{
    /**
     * @param QueuedTaskInterface $task
     */
    public function setQueuedTask(QueuedTaskInterface $task);
}
