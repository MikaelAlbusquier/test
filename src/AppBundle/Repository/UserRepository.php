<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 * Class UserRepository
 * @package AppBundle\Repository
 */
class UserRepository extends EntityRepository
{
    /**
     * @param string $name
     * @return User|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findUniqueByName($name)
    {
        $qb = $this->createQueryBuilder('user');

        $qb
            ->where('user.name = :name')
            ->setParameter('name', $name)
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }
}
