<?php

namespace AppBundle\Model\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait LifeCycleDateTimeTrait
 * @package AppBundle\Model\Traits
 */
trait LifeCycleDateTimeTrait
{
    use CreatedTrait,
        ModifiedTrait;

    /**
     * This a method to update any fields before persisting to database.
     *
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function prePersist()
    {
        $now = new \DateTime();

        if ($this->getCreated() == null) {
            $this->setCreated($now);
        }

        $this->setModified($now);
    }
}

