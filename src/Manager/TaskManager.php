<?php

namespace Glooby\TaskBundle\Manager;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NoResultException;
use Glooby\TaskBundle\Entity\QueuedTask;
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
        $run = new QueuedTask($service, $params, $executeAt);
        $this->populateSchedule($run, $service);
        $this->doctrine->getManager()->persist($run);
        return $run;
    }

    /**
     * @param QueuedTaskInterface $run
     */
    public function start(QueuedTaskInterface $run)
    {
        $run->start();
        $this->doctrine->getManager()->flush();
    }

    /**
     * @param string $service
     * @param array $params
     * @return QueuedTaskInterface
     */
    public function run($service, array $params = null)
    {
        $run = new QueuedTask($service, $params);
        $run->start();
        $this->populateSchedule($run, $service);

        $this->doctrine->getManager()->persist($run);
        $this->doctrine->getManager()->flush();

        return $run;
    }

    /**
     * @param QueuedTaskInterface $run
     * @param $response
     */
    public function success(QueuedTaskInterface $run, $response)
    {
        $run->success($response);
        $this->doctrine->getManager()->flush();
    }

    /**
     * @param QueuedTaskInterface $run
     * @param $response
     */
    public function failure(QueuedTaskInterface $run, $response)
    {
        $run->failure($response);
        $this->doctrine->getManager()->flush();
    }

    /**
     * @param QueuedTaskInterface $run
     * @param string $service
     */
    private function populateSchedule(QueuedTaskInterface $run, $service)
    {
        try {
            $schedule = $this->doctrine->getManager()
                ->getRepository('GloobyTaskBundle:Schedule')
                ->findByName($service);

            $run->setSchedule($schedule);
        } catch (NoResultException $e) {

        }
    }
}
