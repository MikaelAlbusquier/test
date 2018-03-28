<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Drink;
use Doctrine\ORM\EntityRepository;

/**
 * Class DrinkRepository
 * @package AppBundle\Repository
 */
class DrinkRepository extends EntityRepository
{
    /**
     * @param string $name
     * @return Drink|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findUniqueByName($name)
    {
        $qb = $this->createQueryBuilder('drink');

        $qb
            ->where('drink.name = :name')
            ->setParameter('name', $name)
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }
}
