<?php

namespace Glooby\TaskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *   name="task_queue",
 *   options={"collate"="utf8_swedish_ci"},
 *   indexes={
 *     @ORM\Index(name="execute_at_idx", columns={"execute_at"}),
 *     @ORM\Index(name="status_idx",     columns={"status"}),
 *     @ORM\Index(name="name_idx",       columns={"name"})
 *   }
 * )
 * @ORM\Entity(repositoryClass="Glooby\TaskBundle\Entity\QueuedTaskRepository")
 */
class QueuedTask extends \Glooby\TaskBundle\Model\QueuedTask
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

    /**
     * @var array
     *
     * @ORM\Column(name="params", type="json_array", nullable=true)
     */
    protected $params = [];

    /**
     * @var Schedule
     *
     * @ORM\ManyToOne(targetEntity="Schedule", inversedBy="runs")
     * @ORM\JoinColumn(name="schedule_id", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */
    protected $schedule;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="execute_at", type="datetime", nullable=false)
     */
    protected $executeAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="started", type="datetime", nullable=true)
     */
    protected $started;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="finished", type="datetime", nullable=true)
     */
    protected $finished;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="result", type="text", nullable=true)
     */
    protected $result;

    /**
     * @var int
     *
     * @ORM\Column(name="progress", type="integer", nullable=true, options={"default": 0})
     */
    protected $progress;

    /**
     * @var int
     *
     * @ORM\Column(name="progress_info", type="string", nullable=true)
     */
    protected $progressInfo;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", options={"default": "queued"})
     */
    protected $status = self::STATUS_QUEUED;

    /**
     * @var string
     *
     * @ORM\Column(name="resolution", type="string", options={"default": "queued"})
     */
    protected $resolution = self::RESOLUTION_QUEUED;
}
