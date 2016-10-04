<?php

namespace Glooby\TaskBundle\Queue;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection;
use Glooby\TaskBundle\Annotation\Schedule;
use Glooby\TaskBundle\Task\TaskInterface;

/**
 * This task is used to test that the whole scheduling chain works.
 *
 * @Schedule("@daily")
 *
 * @author Emil Kilhage
 */
class QueuePruner implements TaskInterface
{
    /**
     * @var ManagerRegistry
     */
    protected $doctrine;

    /**
     * @param ManagerRegistry $doctrine
     */
    public function setDoctrine($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * {@inheritdoc}
     */
    public function run(array $params = [])
    {
        /** @var Connection $connection */
        $connection = $this->doctrine->getConnection();

        $deleted = $connection->exec('DELETE FROM task_queue
            WHERE resolution = "success"
              AND created <= DATE_SUB(NOW(), INTERVAL 1 MONTH)');

        $deleted += $connection->exec('DELETE FROM task_queue
            WHERE resolution != "success"
              AND created <= DATE_SUB(NOW(), INTERVAL 3 MONTH)');

        return sprintf('%d deleted', $deleted);
    }

    /**
     *
     */
    public function all()
    {
        /** @var Connection $connection */
        $connection = $this->doctrine->getConnection();
        $deleted = $connection->exec('DELETE FROM task_queue');
        return sprintf('%d deleted', $deleted);
    }
}
