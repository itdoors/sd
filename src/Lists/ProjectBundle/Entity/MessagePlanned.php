<?php

namespace Lists\ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MessagePlanned
 */
class MessagePlanned extends Message
{

    /**
     * @var \DateTime
     */
    private $showed;


    /**
     * Set showed
     *
     * @param \DateTime $showed
     * @return MessagePlanned
     */
    public function setShowed($showed)
    {
        $this->showed = $showed;
    
        return $this;
    }

    /**
     * Get showed
     *
     * @return \DateTime 
     */
    public function getShowed()
    {
        return $this->showed;
    }
}