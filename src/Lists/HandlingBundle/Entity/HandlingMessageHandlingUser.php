<?php

namespace Lists\HandlingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HandlingMessageHandlingUser
 */
class HandlingMessageHandlingUser
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Lists\HandlingBundle\Entity\HandlingUser
     */
    private $handlingUser;

    /**
     * @var \Lists\HandlingBundle\Entity\HandlingMessage
     */
    private $handlingMessage;


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
     * Set handlingUser
     *
     * @param \Lists\HandlingBundle\Entity\HandlingUser $handlingUser
     *
     * @return HandlingMessageHandlingUser
     */
    public function setHandlingUser(\Lists\HandlingBundle\Entity\HandlingUser $handlingUser = null)
    {
        $this->handlingUser = $handlingUser;

        return $this;
    }

    /**
     * Get handlingUser
     *
     * @return \Lists\HandlingBundle\Entity\HandlingUser 
     */
    public function getHandlingUser()
    {
        return $this->handlingUser;
    }

    /**
     * Set handlingMessage
     *
     * @param \Lists\HandlingBundle\Entity\HandlingMessage $handlingMessage
     *
     * @return HandlingMessageHandlingUser
     */
    public function setHandlingMessage(\Lists\HandlingBundle\Entity\HandlingMessage $handlingMessage = null)
    {
        $this->handlingMessage = $handlingMessage;

        return $this;
    }

    /**
     * Get handlingMessage
     *
     * @return \Lists\HandlingBundle\Entity\HandlingMessage 
     */
    public function getHandlingMessage()
    {
        return $this->handlingMessage;
    }
}
