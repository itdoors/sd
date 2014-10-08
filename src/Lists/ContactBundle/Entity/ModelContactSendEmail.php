<?php

namespace Lists\ContactBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ModelContactSendEmail
 */
class ModelContactSendEmail
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var boolean
     */
    private $isSend;

    /**
     * @var integer
     */
    private $modelContactId;

    /**
     * @var \Lists\ContactBundle\Entity\ModelContact
     */
    private $modelContact;


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
     * Set isSend
     *
     * @param boolean $isSend
     * 
     * @return ModelContactSendEmail
     */
    public function setIsSend($isSend)
    {
        $this->isSend = $isSend;

        return $this;
    }

    /**
     * Get isSend
     *
     * @return boolean 
     */
    public function getIsSend()
    {
        return $this->isSend;
    }

    /**
     * Set modelContactId
     *
     * @param integer $modelContactId
     * 
     * @return ModelContactSendEmail
     */
    public function setModelContactId($modelContactId)
    {
        $this->modelContactId = $modelContactId;

        return $this;
    }

    /**
     * Get modelContactId
     *
     * @return integer 
     */
    public function getModelContactId()
    {
        return $this->modelContactId;
    }

    /**
     * Set modelContact
     *
     * @param \Lists\ContactBundle\Entity\ModelContact $modelContact
     * 
     * @return ModelContactSendEmail
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
     * @ORM\PrePersist
     */
    public function doOnPrePersist()
    {
        // Add your code here
    }
}
