<?php

namespace Glooby\TaskBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *   name="task_schedules",
 *   options={"collate"="utf8_swedish_ci"},
 *   indexes={
 *     @ORM\Index(name="active_idx", columns={"active"})
 *   }
 * )
 *
 * @ORM\Entity(repositoryClass="Glooby\TaskBundle\Entity\ScheduleRepository")
 */
class Schedule extends \Glooby\TaskBundle\Model\Schedule
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    protected $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

    /**
     * @var string
     *
     * @ORM\Column(name="_interval", type="string")
     */
    protected $interval;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean", options={"default": true})
     */
    protected $active = true;

    /**
     * @var int
     *
     * @ORM\Column(name="timeout", type="integer", options={"default": 0})
     */
    protected $timeout = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="version", type="integer", options={"default": 1})
     */
    protected $version = 1;

    /**
     * @var array
     *
     * @ORM\Column(name="params", type="json_array")
     */
    protected $params = [];

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="QueuedTask", mappedBy="schedule")
     */
    protected $runs;
}
