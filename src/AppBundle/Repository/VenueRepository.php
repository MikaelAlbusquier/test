<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Venue;
use Doctrine\ORM\EntityRepository;

/**
 * Class VenueRepository
 * @package AppBundle\Repository
 */
class VenueRepository extends EntityRepository
{
    /**
     * @param string $name
     * @return Venue|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findUniqueByName($name)
    {
        $qb = $this->createQueryBuilder('venue');

        $qb
            ->where('venue.name = :name')
            ->setParameter('name', $name)
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }
}
