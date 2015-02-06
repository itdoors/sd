<?php

namespace SD\ServiceDeskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BusinessRole
 */
class BusinessRole
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \SD\ServiceDeskBundle\Entity\Individual
     */
    protected $individual;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set individual
     *
     * @param \SD\ServiceDeskBundle\Entity\Individual $individual
     * 
     * @return BusinessRole
     */
    public function setIndividual(\SD\ServiceDeskBundle\Entity\Individual $individual = null)
    {
        $this->individual = $individual;

        return $this;
    }

    /**
     * Get individual
     *
     * @return \SD\ServiceDeskBundle\Entity\Individual 
     */
    public function getIndividual()
    {
        return $this->individual;
    }
}
