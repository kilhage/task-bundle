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
     * @var string
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
     * @param string $name
     * @param array $params
     * @param \DateTime $executeAt
     */
    public function __construct($name, array $params = null, \DateTime $executeAt = null)
    {
        $this->name = $name;
        $this->params = null === $params ? $params : [];
        $this->executeAt = null === $executeAt ? new \DateTime() : $executeAt;
        $this->created = new \DateTime();
        $this->status = self::STATUS_PENDING;
        $this->resolution = self::RESOLUTION_PENDING;
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
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * {@inheritdoc}
     */
    public function getExecuteAt()
    {
        return $this->executeAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getSchedule()
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
    public function getResult()
    {
        return $this->result;
    }

    /**
     * {@inheritdoc}
     */
    public function getResolution()
    {
        return $this->resolution;
    }

    /**
     * {@inheritdoc}
     */
    public function getFinished()
    {
        return $this->finished;
    }

    /**
     * {@inheritdoc}
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * {@inheritdoc}
     */
    public function getStarted()
    {
        return $this->started;
    }

    /**
     * @param \DateTime $started
     */
    protected function setStarted(\DateTime $started)
    {
        $this->started = $started;
    }

    /**
     * @param \DateTime $finished
     */
    protected function setFinished(\DateTime $finished)
    {
        $this->finished = $finished;
    }

    /**
     * @param string $status
     */
    protected function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @param string $resolution
     */
    protected function setResolution($resolution)
    {
        $this->resolution = $resolution;
    }

    /**
     * @param \DateTime $result
     */
    protected function setResult(\DateTime $result)
    {
        $this->result = $result;
    }

    /**
     * @param string $resolution
     * @param mixed $response
     */
    protected function resolve($resolution, $response)
    {
        if (!empty($response)) {
            $this->setResult(print_r($response, true));
        }

        $this->setStatus(QueuedTaskInterface::STATUS_DONE);
        $this->setResolution($resolution);
        $this->setFinished(new \DateTime());
    }

    /**
     * {@inheritdoc}
     */
    public function start()
    {
        $this->setStatus(QueuedTaskInterface::STATUS_RUNNING);
        $this->setStarted(new \DateTime());
    }

    /**
     * {@inheritdoc}
     */
    public function success($response)
    {
        $this->resolve(QueuedTaskInterface::RESOLUTION_SUCCESS, $response);
    }

    /**
     * {@inheritdoc}
     */
    public function failure($response)
    {
        $this->resolve(QueuedTaskInterface::RESOLUTION_FAILURE, $response);
    }
}
