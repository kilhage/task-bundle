<?php

namespace Glooby\TaskBundle\Task;

/**
 * @author Emil Kilhage
 */
interface TaskInterface
{
    /**
     * @param array $params
     *
     * @return array
     */
    public function run(array $params = []);
}
