<?php

namespace Glooby\TaskBundle\Task;

use Glooby\TaskBundle\Annotation\Schedule;

/**
 * This task is used to test that the whole scheduling chain works.
 *
 * @Schedule("*", active=false)
 *
 * @author Emil Kilhage
 */
class PingTask implements TaskInterface
{
    /**
     * {@inheritdoc}
     */
    public function run(array $params = [])
    {
        return 'pong';
    }
}
