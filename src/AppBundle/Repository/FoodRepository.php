<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Food;
use Doctrine\ORM\EntityRepository;

/**
 * Class FoodRepository
 * @package AppBundle\Repository
 */
class FoodRepository extends EntityRepository
{
    /**
     * @param string $name
     * @return Food|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findUniqueByName($name)
    {
        $qb = $this->createQueryBuilder('food');

        $qb
            ->where('food.name = :name')
            ->setParameter('name', $name)
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }
}
