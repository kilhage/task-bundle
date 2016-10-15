<?php

namespace Glooby\TaskBundle\Model;

use Cron\CronExpression;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Emil Kilhage
 */
class Schedule implements ScheduleInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var \DateTime
     */
    protected $created;

    /**
     * @var string
     */
    protected $interval;

    /**
     * @var bool
     */
    protected $active = true;

    /**
     * @var int
     */
    protected $timeout = 0;

    /**
     * @var int
     */
    protected $version = 1;

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @var ArrayCollection
     */
    protected $runs;

    /**
     * Schedule constructor.
     */
    public function __construct()
    {
        $this->created = new \DateTime();
        $this->runs = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * {@inheritdoc}
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * {@inheritdoc}
     */
    public function parseExpression()
    {
        return CronExpression::factory($this->getInterval());
    }

    /**
     * {@inheritdoc}
     */
    public function getInterval()
    {
        return $this->interval;
    }

    /**
     * {@inheritdoc}
     */
    public function setInterval($interval)
    {
        $this->interval = $interval;
    }

    /**
     * {@inheritdoc}
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * {@inheritdoc}
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * {@inheritdoc}
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * {@inheritdoc}
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }

    /**
     * {@inheritdoc}
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * {@inheritdoc}
     */
    public function hasParams()
    {
        return count($this->params) > 0;
    }

    /**
     * {@inheritdoc}
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     * {@inheritdoc}
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * {@inheritdoc}
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }
}
