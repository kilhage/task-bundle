<?php

namespace Glooby\TaskBundle\Model;

/**
 * @author Emil Kilhage
 */
class QueuedTask implements QueuedTaskInterface
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
     * @var array
     */
    protected $params = [];

    /**
     * @var ScheduleInterface
     */
    protected $schedule;

    /**
     * @var \DateTime
     */
    protected $executeAt;

    /**
     * @var \DateTime
     */
    protected $started;

    /**
     * @var \DateTime
     */
    protected $finished;

    /**
     * @var \DateTime
     */
    protected $result;

    /**
     * @var string
     */
    protected $status = self::STATUS_PENDING;

    /**
     * @var string
     */
    protected $resolution = self::RESOLUTION_PENDING;

    /**
     * QueuedTask constructor.
     */
    public function __construct()
    {
        $this->created = new \DateTime();
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
    public function getExecuteAt(): \DateTime
    {
        return $this->executeAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setExecuteAt(\DateTime $executeAt)
    {
        $this->executeAt = $executeAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getSchedule(): ScheduleInterface
    {
        return $this->schedule;
    }

    /**
     * {@inheritdoc}
     */
    public function setSchedule(ScheduleInterface $schedule)
    {
        $this->schedule = $schedule;
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
    public function setParams(array $params = null)
    {
        $this->params = $params;
    }

    /**
     * {@inheritdoc}
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * {@inheritdoc}
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getResolution(): string
    {
        return $this->resolution;
    }

    /**
     * {@inheritdoc}
     */
    public function setResolution(string $resolution)
    {
        $this->resolution = $resolution;
    }

    /**
     * {@inheritdoc}
     */
    public function getFinished(): \DateTime
    {
        return $this->finished;
    }

    /**
     * {@inheritdoc}
     */
    public function setFinished(\DateTime $finished)
    {
        $this->finished = $finished;
    }

    /**
     * {@inheritdoc}
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * {@inheritdoc}
     */
    public function setStatus(string $status)
    {
        $this->status = $status;
    }

    /**
     * {@inheritdoc}
     */
    public function getStarted(): \DateTime
    {
        return $this->started;
    }

    /**
     * {@inheritdoc}
     */
    public function setStarted(\DateTime $started)
    {
        $this->started = $started;
    }
}
