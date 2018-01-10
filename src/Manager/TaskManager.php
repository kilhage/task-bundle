<?php

namespace Glooby\TaskBundle\Manager;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NoResultException;
use Glooby\TaskBundle\Entity\QueuedTask;
use Glooby\TaskBundle\Entity\ScheduleRepository;
use Glooby\TaskBundle\Model\QueuedTaskInterface;

/**
 * @author Emil Kilhage
 */
class TaskManager
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
     * @param string $service
     * @param \DateTime|null $executeAt
     * @param array|null $params
     * @return QueuedTaskInterface
     */
    public function queue($service, \DateTime $executeAt = null, array $params = null)
    {
        $task = new QueuedTask($service, $params, $executeAt);
        $this->populateSchedule($task, $service);
        $this->doctrine->getManager()->persist($task);
        return $task;
    }

    /**
     * @param QueuedTaskInterface $task
     */
    public function start(QueuedTaskInterface $task)
    {
        $task->start();
    }

    /**
     * @param string $service
     * @param array $params
     * @return QueuedTaskInterface
     */
    public function run($service, array $params = null)
    {
        $task = new QueuedTask($service, $params);
        $task->start();
        $this->populateSchedule($task, $service);

        $this->doctrine->getManager()->persist($task);
        $this->save($task);

        return $task;
    }

    /**
     * @param QueuedTaskInterface $task
     * @param $response
     */
    public function success(QueuedTaskInterface $task, $response)
    {
        $task->success($response);
        $this->save($task);
    }

    /**
     * @param QueuedTaskInterface $task
     * @param $response
     */
    public function failure(QueuedTaskInterface $task, $response)
    {
        $task->failure($response);
        $this->save($task);
    }

    /**
     * @param QueuedTaskInterface $task
     * @param string $service
     */
    private function populateSchedule(QueuedTaskInterface $task, $service)
    {
        try {
            /** @var ScheduleRepository $repo */
            $repo = $this->doctrine->getManager()
                ->getRepository('GloobyTaskBundle:Schedule');
            $schedule = $repo->findByName($service);
            $task->setSchedule($schedule);
        } catch (NoResultException $e) {
            // ignore if not found
        }
    }

    /**
     * @param QueuedTaskInterface $task
     */
    private function save(QueuedTaskInterface $task)
    {
        $this->doctrine->getManager()->flush();
    }
}
