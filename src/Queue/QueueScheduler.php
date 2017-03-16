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
        $queueRepo = $this->doctrine->getManager()
            ->getRepository('GloobyTaskBundle:QueuedTask');

        $scheduleRepo = $this->doctrine->getManager()
            ->getRepository('GloobyTaskBundle:Schedule');

        foreach ($scheduleRepo->findActive() as $schedule) {
            if (!$queueRepo->isQueued($schedule->getName())) {
                $this->queue($schedule);
            }
        }

        $this->doctrine->getManager()->flush();
    }

    /**
     * @param ScheduleInterface $schedule
     */
    private function queue(ScheduleInterface $schedule)
    {
        $executeAt = $this->getExecuteAt($schedule);

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
        return $this->checkRunDate($schedule, $schedule->parseExpression()->getNextRunDate());
    }

    /**
     * @param ScheduleInterface $schedule
     * @return \DateTime|null
     */
    private function checkPreviousRunDate(ScheduleInterface $schedule)
    {
        return $this->checkRunDate($schedule, $schedule->parseExpression()->getPreviousRunDate());
    }

    /**
     * @param ScheduleInterface $schedule
     * @param \DateTime $date
     * @return \DateTime|null
     */
    private function checkRunDate(ScheduleInterface $schedule, \DateTime $date)
    {
        $queueRepo = $this->getQueuedTaskRepo();
        $executeAt = null;

        try {
            $queueRepo->getByNameAndExecuteAt($schedule->getName(), $date);
        } catch (NoResultException $e) {
            $executeAt = $date;
        }

        return $executeAt;
    }

    /**
     * @param ScheduleInterface $schedule
     * @return \DateTime|null
     */
    private function checkExecutedBeforeNow(ScheduleInterface $schedule)
    {
        $queueRepo = $this->getQueuedTaskRepo();
        $executeAt = null;

        try {
            $queueRepo->getByNameAndExecuteAtBeforeNow($schedule->getName());
        } catch (NoResultException $e) {
            $executeAt = $schedule->parseExpression()->getPreviousRunDate();
        }

        return $executeAt;
    }

    /**
     * @return QueuedTaskRepository
     */
    private function getQueuedTaskRepo()
    {
        return $this->doctrine->getManager()
            ->getRepository('GloobyTaskBundle:QueuedTask');
    }

    /**
     * @param ScheduleInterface $schedule
     * @return \DateTime|null
     */
    private function getExecuteAt(ScheduleInterface $schedule)
    {
        $executeAt = $this->checkExecutedBeforeNow($schedule);

        if (null === $executeAt) {
            $executeAt = $this->checkPreviousRunDate($schedule);
        }

        if (null === $executeAt) {
            $executeAt = $this->checkNextRunDate($schedule);
        }

        return $executeAt;
    }
}
