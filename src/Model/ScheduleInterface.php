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
     * @return CronExpression
     */
    public function parseExpression() : CronExpression;

    /**
     * @return string
     */
    public function getRunEvery() : string;

    /**
     * @param string $runEvery
     */
    public function setRunEvery(string $runEvery);

    /**
     * @return boolean
     */
    public function isActive() : bool;

    /**
     * @param boolean $active
     */
    public function setActive(bool $active);

    /**
     * @return int
     */
    public function getTimeout() : int;

    /**
     * @param int $timeout
     */
    public function setTimeout(int $timeout);

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
    public function setParams(array $params);

    /**
     * @return int
     */
    public function getVersion() : int;

    /**
     * @param int $version
     */
    public function setVersion(int $version);
}
