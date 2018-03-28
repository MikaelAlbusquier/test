<?php

namespace AppBundle\Model\Traits;

/**
 * Trait ModifiedTrait
 * @package AppBundle\Model\Traits
 */
trait ModifiedTrait
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modified", type="datetime")
     */
    private $modified;

    /**
     * Set modified
     *
     * @param \DateTime $modified
     *
     * @return $this
     */
    public function setModified(\DateTime $modified)
    {
        $this->modified = $modified;

        return $this;
    }

    /**
     * Get modified
     *
     * @return \DateTime
     */
    public function getModified()
    {
        return $this->modified;
    }
}