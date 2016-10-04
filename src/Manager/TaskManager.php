<?php

namespace Glooby\TaskBundle\Manager;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NoResultException;
use Glooby\TaskBundle\Entity\QueuedTask;

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
     * @return QueuedTask
     */
    public function queue(string $service, \DateTime $executeAt = null, array $params = null): QueuedTask
    {
        $run = new QueuedTask();
        $run->setName($service);
        $run->setParams($params);
        $run->setStatus(QueuedTask::STATUS_PENDING);
        $run->setExecuteAt(null === $executeAt ? new \DateTime() : $executeAt);

        try {
            $schedule = $this->doctrine->getManager()
                ->getRepository('GloobyTaskBundle:Schedule')
                ->findByName($service);

            $run->setSchedule($schedule);
        } catch (NoResultException $e) {

        }

        $this->doctrine->getManager()->persist($run);

        return $run;
    }

    /**
     * @param QueuedTask $run
     */
    public function start(QueuedTask $run)
    {
        $run->setStatus(QueuedTask::STATUS_RUNNING);
        $run->setStarted(new \DateTime());
        $this->doctrine->getManager()->flush();
    }

    /**
     * @param string $service
     * @param array $params
     * @return QueuedTask
     */
    public function run(string $service, array $params = null): QueuedTask
    {
        $run = new QueuedTask();
        $run->setName($service);
        $run->setParams($params);
        $run->setStatus(QueuedTask::STATUS_RUNNING);
        $run->setExecuteAt(new \DateTime());
        $run->setStarted(new \DateTime());

        try {
            $schedule = $this->doctrine->getManager()
                ->getRepository('GloobyTaskBundle:Schedule')
                ->findByName($service);

            $run->setSchedule($schedule);
        } catch (NoResultException $e) {

        }

        $this->doctrine->getManager()->persist($run);
        $this->doctrine->getManager()->flush();

        return $run;
    }

    /**
     * @param QueuedTask $run
     * @param $response
     */
    public function success(QueuedTask $run, $response)
    {
        if (!empty($response)) {
            $run->setResult(print_r($response, true));
        }

        $run->setStatus(QueuedTask::STATUS_DONE);
        $run->setResolution(QueuedTask::RESOLUTION_SUCCESS);
        $run->setFinished(new \DateTime());

        $this->doctrine->getManager()->flush();
    }

    /**
     * @param QueuedTask $run
     * @param $response
     */
    public function failure(QueuedTask $run, $response)
    {
        if (!empty($response)) {
            $run->setResult(print_r($response, true));
        }

        $run->setStatus(QueuedTask::STATUS_DONE);
        $run->setResolution(QueuedTask::RESOLUTION_FAILURE);
        $run->setFinished(new \DateTime());

        $this->doctrine->getManager()->flush();
    }
}
