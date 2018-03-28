<?php

namespace AppBundle\Entity;

use AppBundle\Model\Traits\AutoGeneratedIdTrait;
use AppBundle\Model\Traits\LifeCycleDateTimeTrait;
use AppBundle\Model\Traits\UniqueNameTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Food
 *
 * @ORM\Table(name="food")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FoodRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Food
{
    use AutoGeneratedIdTrait,
        UniqueNameTrait,
        LifeCycleDateTimeTrait;

    /**
     * Many Foods have many Users.
     *
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="User", mappedBy="foods")
     * @ORM\JoinTable(name="user_food")
     */
    private $users;

    /**
     * Many Drinks have many Venues.
     *
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Venue", mappedBy="foods")
     * @ORM\JoinTable(name="venue_food")
     */
    private $venues;

    /**
     * Food constructor.
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->venues = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function addUser(User $user)
    {
        $this->users->add($user);

        return $this;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function removeUser(User $user)
    {
        $this->users->removeElement($user);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getVenues()
    {
        return $this->venues;
    }

    /**
     * @param Venue $venue
     * @return $this
     */
    public function addVenue(Venue $venue)
    {
        $this->venues->add($venue);

        return $this;
    }

    /**
     * @param Venue $venue
     * @return $this
     */
    public function removeVenue(Venue $venue)
    {
        $this->venues->removeElement($venue);

        return $this;
    }
}

