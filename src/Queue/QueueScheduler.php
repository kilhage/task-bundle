<?php

namespace Glooby\TaskBundle\Queue;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NoResultException;
use Glooby\TaskBundle\Entity\QueuedTask;
use Glooby\TaskBundle\Entity\QueuedTaskRepository;
use Glooby\TaskBundle\Manager\TaskManager;
use Glooby\TaskBundle\Model\ScheduleInterface;

/**
 * @author Emil Kilhage
 */
class QueueScheduler
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
     * @var TaskManager
     */
    private $taskManager;

    /**
     * @param TaskManager $taskManager
     */
    public function setTaskManager(TaskManager $taskManager)
    {
        $this->taskManager = $taskManager;
    }

    /**
     *
     */
    public function schedule()
    {
        $repo = $this->doctrine->getManager()
            ->getRepository('GloobyTaskBundle:Schedule');

        foreach ($repo->findActive() as $schedule) {
            $this->queue($schedule);
        }

        $this->doctrine->getManager()->flush();
    }

    /**
     * @param ScheduleInterface $schedule
     */
    private function queue(ScheduleInterface $schedule)
    {
        $executeAt = $this->checkPreviousRunDate($schedule);

        if (null === $executeAt) {
            $executeAt = $this->checkNextRunDate($schedule);
        }

        if (null !== $executeAt) {
            $this->taskManager->queue($schedule->getName(), $executeAt, $schedule->getParams());
        }
    }

    /**
     * @param ScheduleInterface $schedule
     * @return \DateTime|null
     */
    private function checkNextRunDate(ScheduleInterface $schedule)
    {
        /** @var QueuedTaskRepository $queueRepo */
        $queueRepo = $this->doctrine->getManager()
            ->getRepository('GloobyTaskBundle:QueuedTask');

        $executeAt = null;
        $expression = $schedule->parseExpression();
        $nextExecuteAt = $expression->getNextRunDate();

        try {
            $queueRepo->getByNameAndExecuteAt($schedule->getName(), $nextExecuteAt);
        } catch (NoResultException $e) {
            $executeAt = $nextExecuteAt;
        }

        return $executeAt;
    }

    /**
     * @param ScheduleInterface $schedule
     * @return \DateTime|null
     */
    private function checkPreviousRunDate(ScheduleInterface $schedule)
    {
        /** @var QueuedTaskRepository $queueRepo */
        $queueRepo = $this->doctrine->getManager()
            ->getRepository('GloobyTaskBundle:QueuedTask');

        $executeAt = null;
        $expression = $schedule->parseExpression();

        try {
            $queueRepo->getByNameAndExecuteAtBeforeNow($schedule->getName());
            return $executeAt;
        } catch (NoResultException $e) {
            $executeAt = $expression->getPreviousRunDate();
        }

        return $executeAt;
    }
}
