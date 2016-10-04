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
     * @return mixed
     */
    public function run(array $params = []);
}
