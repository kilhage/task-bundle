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
    protected $runEvery;

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
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    /**
     * {@inheritdoc}
     */
    public function setCreated(\DateTime $created)
    {
        $this->created = $created;
    }

    /**
     * {@inheritdoc}
     */
    public function parseExpression(): CronExpression
    {
        return CronExpression::factory($this->getRunEvery());
    }

    /**
     * {@inheritdoc}
     */
    public function getRunEvery(): string
    {
        return $this->runEvery;
    }

    /**
     * {@inheritdoc}
     */
    public function setRunEvery(string $runEvery)
    {
        $this->runEvery = $runEvery;
    }

    /**
     * {@inheritdoc}
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * {@inheritdoc}
     */
    public function setActive(bool $active)
    {
        $this->active = $active;
    }

    /**
     * {@inheritdoc}
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * {@inheritdoc}
     */
    public function setTimeout(int $timeout)
    {
        $this->timeout = $timeout;
    }

    /**
     * {@inheritdoc}
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * {@inheritdoc}
     */
    public function hasParams(): bool
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
    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * {@inheritdoc}
     */
    public function setVersion(int $version)
    {
        $this->version = $version;
    }
}
