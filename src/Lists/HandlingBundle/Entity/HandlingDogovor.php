<?php

namespace Lists\HandlingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HandlingDogovor
 */
class HandlingDogovor
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $handlingId;

    /**
     * @var integer
     */
    private $dogovorId;

    /**
     * @var \Lists\DogovorBundle\Entity\Dogovor
     */
    private $dogovor;

    /**
     * @var \Lists\HandlingBundle\Entity\Handling
     */
    private $handling;


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
     * Set handlingId
     *
     * @param integer $handlingId
     * @return HandlingDogovor
     */
    public function setHandlingId($handlingId)
    {
        $this->handlingId = $handlingId;
    
        return $this;
    }

    /**
     * Get handlingId
     *
     * @return integer 
     */
    public function getHandlingId()
    {
        return $this->handlingId;
    }

    /**
     * Set dogovorId
     *
     * @param integer $dogovorId
     * @return HandlingDogovor
     */
    public function setDogovorId($dogovorId)
    {
        $this->dogovorId = $dogovorId;
    
        return $this;
    }

    /**
     * Get dogovorId
     *
     * @return integer 
     */
    public function getDogovorId()
    {
        return $this->dogovorId;
    }

    /**
     * Set dogovor
     *
     * @param \Lists\DogovorBundle\Entity\Dogovor $dogovor
     * @return HandlingDogovor
     */
    public function setDogovor(\Lists\DogovorBundle\Entity\Dogovor $dogovor = null)
    {
        $this->dogovor = $dogovor;
    
        return $this;
    }

    /**
     * Get dogovor
     *
     * @return \Lists\DogovorBundle\Entity\Dogovor 
     */
    public function getDogovor()
    {
        return $this->dogovor;
    }

    /**
     * Set handling
     *
     * @param \Lists\HandlingBundle\Entity\Handling $handling
     * @return HandlingDogovor
     */
    public function setHandling(\Lists\HandlingBundle\Entity\Handling $handling = null)
    {
        $this->handling = $handling;
    
        return $this;
    }

    /**
     * Get handling
     *
     * @return \Lists\HandlingBundle\Entity\Handling 
     */
    public function getHandling()
    {
        return $this->handling;
    }
}