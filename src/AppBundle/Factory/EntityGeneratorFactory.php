<?php

namespace AppBundle\Factory;

use AppBundle\Entity\Drink;
use AppBundle\Entity\Event;
use AppBundle\Entity\Food;
use AppBundle\Entity\User;
use AppBundle\Entity\Venue;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class EntityGeneratorFactory
 * @package AppBundle\Factory
 */
class EntityGeneratorFactory
{
    const TYPE_DRINK    = 'drink';
    const TYPE_EVENT    = 'event';
    const TYPE_FOOD     = 'food';
    const TYPE_USER     = 'user';
    const TYPE_VENUE    = 'venue';

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * EntityGeneratorFactory constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $type
     * @return Drink|Food|User|Venue|null
     */
    public function generateEntity(string $type)
    {
        $entity = null;

        switch ($type) {
            case self::TYPE_DRINK:
                $entity = new Drink();
                break;
            case self::TYPE_EVENT:
                $entity = new Event();
                break;
            case self::TYPE_FOOD:
                $entity = new Food();
                break;
            case self::TYPE_USER:
                $entity = new User();
                break;
            case self::TYPE_VENUE:
                $entity = new Venue();
                break;
            default:
                break;
        }

        if (!is_null($entity)) {
            $this->entityManager->persist($entity);
        }

        return $entity;
    }
}