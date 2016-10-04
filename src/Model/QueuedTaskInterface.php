<?php

namespace Glooby\TaskBundle\Model;

/**
 * @author Emil Kilhage
 */
interface QueuedTaskInterface
{
    const STATUS_PENDING = 'pending';
    const STATUS_RUNNING = 'running';
    const STATUS_DONE    = 'done';

    const RESOLUTION_PENDING = 'pending';
    const RESOLUTION_SUCCESS = 'success';
    const RESOLUTION_FAILURE = 'failure';

    /**
     * @return int
     */
    public function getId() : int;

    /**
     * @param int $id
     */
    public function setId(int $id);

    /**
     * @return string
     */
    public function getName() : string;

    /**
     * @param string $name
     */
    public function setName(string $name);

    /**
     * @return \DateTime
     */
    public function getCreated() : \DateTime;

    /**
     * @param \DateTime $created
     */
    public function setCreated(\DateTime $created);

    /**
     * @return \DateTime
     */
    public function getExecuteAt() : \DateTime;

    /**
     * @param \DateTime $executeAt
     */
    public function setExecuteAt(\DateTime $executeAt);

    /**
     * @return ScheduleInterface
     */
    public function getSchedule() : ScheduleInterface;

    /**
     * @param ScheduleInterface $schedule
     */
    public function setSchedule(ScheduleInterface $schedule);

    /**
     * @return array
     */
    public function getParams() : array;

    /**
     * @return bool
     */
    public function hasParams() : bool;

    /**
     * @param array $params
     */
    public function setParams(array $params = null);

    /**
     * @return string
     */
    public function getResult();

    /**
     * @param string $result
     */
    public function setResult($result);

    /**
     * @return string
     */
    public function getResolution() : string;

    /**
     * @param string $resolution
     */
    public function setResolution(string $resolution);

    /**
     * @return \DateTime
     */
    public function getFinished() : \DateTime;

    /**
     * @param \DateTime $finished
     */
    public function setFinished(\DateTime $finished);

    /**
     * @return string
     */
    public function getStatus() : string;

    /**
     * @param string $status
     */
    public function setStatus(string $status);

    /**
     * @return \DateTime
     */
    public function getStarted() : \DateTime;

    /**
     * @param \DateTime $started
     */
    public function setStarted(\DateTime $started);
}