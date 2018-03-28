<?php

namespace AppBundle\Service;

use AppBundle\Entity\Drink;
use AppBundle\Entity\Food;
use AppBundle\Entity\User;
use AppBundle\Entity\Venue;
use AppBundle\Factory\EntityGeneratorFactory;
use AppBundle\Repository\DrinkRepository;
use AppBundle\Repository\FoodRepository;
use AppBundle\Repository\UserRepository;
use AppBundle\Repository\VenueRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class DataImporter
 * @package AppBundle\Service
 */
class DataImporter
{
    /**
     * @var EntityGeneratorFactory
     */
    private $entityGeneratorFactory;

    /**
     * @var string
     */
    private $usersFilename;

    /**
     * @var string
     */
    private $venuesFilename;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var DrinkRepository
     */
    private $drinkRepository;

    /**
     * @var FoodRepository
     */
    private $foodRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var VenueRepository
     */
    private $venueRepository;

    /**
     * DataImporter constructor.
     * @param EntityManagerInterface    $entityManager
     * @param EntityGeneratorFactory    $entityGeneratorFactory
     * @param string                    $usersFilename
     * @param string                    $venuesFilename
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        EntityGeneratorFactory $entityGeneratorFactory,
        $usersFilename,
        $venuesFilename
    )
    {
        $this->entityGeneratorFactory = $entityGeneratorFactory;
        $this->usersFilename = $usersFilename;
        $this->venuesFilename = $venuesFilename;
        $this->entityManager = $entityManager;
        $this->drinkRepository = $entityManager->getRepository('AppBundle:Drink');
        $this->foodRepository = $entityManager->getRepository('AppBundle:Food');
        $this->userRepository = $entityManager->getRepository('AppBundle:User');
        $this->venueRepository = $entityManager->getRepository('AppBundle:Venue');
    }

    /**
     * @return bool
     */
    public function importVenueData()
    {
        $jsonContent = file_get_contents('./json_data/' . $this->venuesFilename);

        $content = json_decode($jsonContent, true);

        foreach ($content as $data) {
            $venue = $this->venueRepository->findUniqueByName($data['name']);

            if (!$venue instanceof Venue) {
                $venue = $this->entityGeneratorFactory->generateEntity(EntityGeneratorFactory::TYPE_VENUE);

                $venue-> setName($data['name']);

                $this->entityManager->persist($venue);
                $this->entityManager->flush();
            }

            if (isset($data['drinks'])) {
                $this->importDrinkData($data['drinks'], null, $venue);
            }

            if (isset($data['food'])) {
                $this->importFoodData($data['food'], null, $venue);
            }
        }

        return true;
    }

    /**
     * @return bool
     */
    public function importUserData()
    {
        $jsonContent = file_get_contents('./json_data/' . $this->usersFilename);

        $content = json_decode($jsonContent, true);

        foreach ($content as $data) {
            $user = $this->userRepository->findUniqueByName($data['name']);

            if (!$user instanceof User) {
                $user = $this->entityGeneratorFactory->generateEntity(EntityGeneratorFactory::TYPE_USER);

                $user->setName($data['name']);

                $this->entityManager->persist($user);
                $this->entityManager->flush();
            }

            if (isset($data['drinks'])) {
                $this->importDrinkData($data['drinks'], $user);
            }

            if (isset($data['wont_eat'])) {
                $this->importFoodData($data['wont_eat'], $user);
            }
        }

        return true;
    }

    /**
     * @param array $drinks
     * @param User|null $user
     * @param Venue|null $venue
     */
    public function importDrinkData(array $drinks, User $user = null, Venue $venue = null)
    {
        foreach ($drinks as $drinkName) {
            $drink = $this->drinkRepository->findUniqueByName($drinkName);

            if (!$drink instanceof Drink) {
                $drink = $this->entityGeneratorFactory->generateEntity(EntityGeneratorFactory::TYPE_DRINK);

                $drink->setName($drinkName);
            }

            if ($user instanceof User) {
                if (!$drink->getUsers()->contains($user)) {
                    $drink->addUser($user);
                    $user->addDrink($drink);
                    $this->entityManager->persist($user);
                }
            }

            if ($venue instanceof Venue) {
                if (!$drink->getVenues()->contains($venue)) {
                    $drink->addVenue($venue);
                    $venue->addDrink($drink);
                    $this->entityManager->persist($venue);
                }
            }

            $this->entityManager->persist($drink);
            $this->entityManager->flush();
        }
    }

    /**
     * @param array $foods
     * @param User|null $user
     * @param Venue|null $venue
     */
    public function importFoodData(array $foods, User $user = null, Venue $venue = null)
    {
        foreach ($foods as $foodName) {
            $food = $this->foodRepository->findUniqueByName($foodName);

            if (!$food instanceof Food) {
                $food = $this->entityGeneratorFactory->generateEntity(EntityGeneratorFactory::TYPE_FOOD);

                $food->setName($foodName);

                $this->entityManager->persist($food);
                $this->entityManager->flush();
            }

            if ($user instanceof User) {
                if (!$food->getUsers()->contains($user)) {
                    $food->addUser($user);
                    $user->addFood($food);
                    $this->entityManager->persist($user);
                }
            }

            if ($venue instanceof Venue) {
                if (!$food->getVenues()->contains($venue)) {
                    $food->addVenue($venue);
                    $venue->addFood($food);
                    $this->entityManager->persist($venue);
                }
            }

            $this->entityManager->persist($food);
            $this->entityManager->flush();
        }
    }
}