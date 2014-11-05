<?php

namespace Lists\HandlingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HandlingMessageModelContact
 */
class HandlingMessageModelContact
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Lists\ContactBundle\Entity\ModelContact
     */
    private $modelContact;

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
     * Set modelContact
     *
     * @param \Lists\ContactBundle\Entity\ModelContact $modelContact
     *
     * @return HandlingMessageModelContact
     */
    public function setModelContact(\Lists\ContactBundle\Entity\ModelContact $modelContact = null)
    {
        $this->modelContact = $modelContact;

        return $this;
    }

    /**
     * Get modelContact
     *
     * @return \Lists\ContactBundle\Entity\ModelContact 
     */
    public function getModelContact()
    {
        return $this->modelContact;
    }

    /**
     * Set handlingMessage
     *
     * @param \Lists\HandlingBundle\Entity\HandlingMessage $handlingMessage
     *
     * @return HandlingMessageModelContact
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
