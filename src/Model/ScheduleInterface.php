<?php

namespace Glooby\TaskBundle\Model;

use Cron\CronExpression;

/**
 * @author Emil Kilhage
 */
interface ScheduleInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     */
    public function setName($name);

    /**
     * @return \DateTime
     */
    public function getCreated();

    /**
     * @param \DateTime $created
     */
    public function setCreated($created);

    /**
     * @return CronExpression
     */
    public function parseExpression();

    /**
     * @return string
     */
    public function getInterval();

    /**
     * @param string $interval
     */
    public function setInterval($interval);

    /**
     * @return boolean
     */
    public function isActive();

    /**
     * @param boolean $active
     */
    public function setActive($active);

    /**
     * @return int
     */
    public function getTimeout();

    /**
     * @param int $timeout
     */
    public function setTimeout($timeout);

    /**
     * @return array
     */
    public function getParams();

    /**
     * @return bool
     */
    public function hasParams();

    /**
     * @param array $params
     */
    public function setParams(array $params);

    /**
     * @return int
     */
    public function getVersion();

    /**
     * @param int $version
     */
    public function setVersion($version);
}
