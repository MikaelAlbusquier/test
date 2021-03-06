<?php

namespace AppBundle\Model\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait AutoGeneratedIdTrait
 * @package AppBundle\Model\Traits
 */
trait AutoGeneratedIdTrait
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}