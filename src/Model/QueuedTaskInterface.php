<?php

namespace Glooby\TaskBundle\Model;

/**
 * @author Emil Kilhage
 */
interface QueuedTaskInterface
{
    const STATUS_QUEUED = 'queued';
    const STATUS_RUNNING = 'running';
    const STATUS_DONE    = 'done';

    const RESOLUTION_QUEUED = 'queued';
    const RESOLUTION_SUCCESS = 'success';
    const RESOLUTION_FAILURE = 'failure';

    /**
     * @return int
     */
    public function getId();

    /**
     * @return int
     */
    public function getPId();

    /**
     * @return int
     */
    public function hasPId();

    /**
     * @param int $pid
     */
    public function setPId(int $pid);

    /**
     * @return string
     */
    public function getName();

    /**
     * @return \DateTime
     */
    public function getCreated();

    /**
     * @return \DateTime
     */
    public function getUpdated();

    /**
     * @param \DateTime $updated
     */
    public function setUpdated(\DateTime $updated);

    /**
     * @return \DateTime
     */
    public function getExecuteAt();

    /**
     * @return ScheduleInterface
     */
    public function getSchedule();

    /**
     * @param ScheduleInterface $schedule
     */
    public function setSchedule(ScheduleInterface $schedule);

    /**
     * @return array
     */
    public function getParams();

    /**
     * @return bool
     */
    public function hasParams();

    /**
     * @return string
     */
    public function getResult();

    /**
     * @return string
     */
    public function getResolution();

    /**
     * @return \DateTime
     */
    public function getFinished();

    /**
     * @return string
     */
    public function getStatus();

    /**
     * @return \DateTime
     */
    public function getStarted();

    /**
     *
     */
    public function start();

    /**
     * @param mixed $response
     */
    public function success($response);

    /**
     * @param mixed $response
     */
    public function failure($response);

    /**
     * @param int $progress
     * @param string|null $info
     */
    public function progress(int $progress, ?string $info = null);
}
