<?php

namespace AppBundle\Service;
use AppBundle\Entity\Drink;
use AppBundle\Entity\Food;
use AppBundle\Entity\User;
use AppBundle\Entity\Venue;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class BestVenueChoice
 * @package AppBundle\Service
 */
class BestVenueChoice
{
    /**
     * @param ArrayCollection $venues
     * @param ArrayCollection $attendees
     * @return array
     */
    public function identify($venues, $attendees)
    {
        $result = [];

        /* @var Venue $venue */
        foreach ($venues as $venue) {
            $result[$venue->getId()] = [
                'okay' => true,
            ];

            $venueDrinks = $venue->getDrinks();
            $venueFoods = $venue->getFoods();

            /* @var User $attendee */
            foreach ($attendees as $attendee) {
                $canEat = $this->canEat($attendee, $venueFoods);
                $hasToDrink = $this->hasToDrink($attendee, $venueDrinks);

                if (!$canEat) {
                    if (array_key_exists('foods', $result[$venue->getId()])) {
                        $result[$venue->getId()]['foods'][] = $attendee->getName();
                    } else {
                        $result[$venue->getId()]['foods'] = [$attendee->getName()];
                    }
                }

                if (!$hasToDrink) {
                    if (array_key_exists('drinks', $result[$venue->getId()])) {
                        $result[$venue->getId()]['drinks'][] = $attendee->getName();
                    } else {
                        $result[$venue->getId()]['drinks'] = [$attendee->getName()];
                    }
                }

                if (!$canEat || !$hasToDrink) {
                    $result[$venue->getId()]['okay'] = false;
                }
            }
        }

        return $result;
    }

    /**
     * @param User $user
     * @param ArrayCollection $drinks
     * @return bool
     */
    public function hasToDrink(User $user, $drinks)
    {
        $userDrinks = $user->getDrinks();

        /* @var Drink $drink */
        foreach ($drinks as $drink) {
            // If it contains at least one drink which user likes then it is fine
            if ($userDrinks->contains($drink)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param User $user
     * @param ArrayCollection $foods
     * @return bool
     */
    public function canEat(User $user, $foods)
    {
        $userFoods = $user->getFoods();

        // USer doesn't mind any food
        if ($userFoods->count() === 0) {
            return true;
        }

        /* @var Food $food */
        foreach ($foods as $food) {
            // If it doesn't contain a food that user mind eating at least once then it is fine
            if (!$userFoods->contains($food)) {
                return true;
            }
        }

        return false;
    }
}