<?php

namespace Glooby\TaskBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Glooby\TaskBundle\Model\ScheduleInterface;

/**
 * @author Emil Kilhage
 */
class ScheduleRepository extends EntityRepository
{
    /**
     * @param string $name
     *
     * @return ScheduleInterface
     *
     * @throws NoResultException
     */
    public function findByName($name)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT r FROM GloobyTaskBundle:Schedule r WHERE r.name = :name')
            ->setParameter('name', $name)
            ->useQueryCache(true)
            ->getSingleResult();
    }

    /**
     * @param array $names
     *
     * @return ScheduleInterface[]
     */
    public function findNotInNames(array $names)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT r FROM GloobyTaskBundle:Schedule r WHERE r.name NOT IN (:names)')
            ->setParameter('names', $names)
            ->useQueryCache(true)
            ->getResult();
    }

    /**
     * @return ScheduleInterface[]
     */
    public function findActive()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT r FROM GloobyTaskBundle:Schedule r WHERE r.active = true')
            ->useQueryCache(true)
            ->getResult();
    }
}
