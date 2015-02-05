<?php

namespace Lists\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * СommercialTender
 */
class СommercialTender
{
    /**
     * @var \DateTime
     */
    private $datetimeDeadline;

    /**
     * @var \DateTime
     */
    private $datetimeOpening;


    /**
     * Set datetimeDeadline
     *
     * @param \DateTime $datetimeDeadline
     * @return СommercialTender
     */
    public function setDatetimeDeadline($datetimeDeadline)
    {
        $this->datetimeDeadline = $datetimeDeadline;
    
        return $this;
    }

    /**
     * Get datetimeDeadline
     *
     * @return \DateTime 
     */
    public function getDatetimeDeadline()
    {
        return $this->datetimeDeadline;
    }

    /**
     * Set datetimeOpening
     *
     * @param \DateTime $datetimeOpening
     * @return СommercialTender
     */
    public function setDatetimeOpening($datetimeOpening)
    {
        $this->datetimeOpening = $datetimeOpening;
    
        return $this;
    }

    /**
     * Get datetimeOpening
     *
     * @return \DateTime 
     */
    public function getDatetimeOpening()
    {
        return $this->datetimeOpening;
    }
}