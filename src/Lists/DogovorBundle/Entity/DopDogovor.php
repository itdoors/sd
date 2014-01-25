<?php

namespace Lists\DogovorBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DopDogovor
 */
class DopDogovor
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $dopDogovorType;

    /**
     * @var string
     */
    private $number;

    /**
     * @var \DateTime
     */
    private $startdatetime;

    /**
     * @var \DateTime
     */
    private $activedatetime;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var boolean
     */
    private $isActive;

    /**
     * @var string
     */
    private $filepath;

    /**
     * @var float
     */
    private $total;

    /**
     * @var \Lists\DogovorBundle\Entity\Dogovor
     */
    private $dogovor;

    /**
     * @var \SD\UserBundle\Entity\Staff
     */
    private $stuff;

    /**
     * @var \SD\UserBundle\Entity\User
     */
    private $user;


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
     * Set dopDogovorType
     *
     * @param string $dopDogovorType
     * @return DopDogovor
     */
    public function setDopDogovorType($dopDogovorType)
    {
        $this->dopDogovorType = $dopDogovorType;
    
        return $this;
    }

    /**
     * Get dopDogovorType
     *
     * @return string 
     */
    public function getDopDogovorType()
    {
        return $this->dopDogovorType;
    }

    /**
     * Set number
     *
     * @param string $number
     * @return DopDogovor
     */
    public function setNumber($number)
    {
        $this->number = $number;
    
        return $this;
    }

    /**
     * Get number
     *
     * @return string 
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set startdatetime
     *
     * @param \DateTime $startdatetime
     * @return DopDogovor
     */
    public function setStartdatetime($startdatetime)
    {
        $this->startdatetime = $startdatetime;
    
        return $this;
    }

    /**
     * Get startdatetime
     *
     * @return \DateTime 
     */
    public function getStartdatetime()
    {
        return $this->startdatetime;
    }

    /**
     * Set activedatetime
     *
     * @param \DateTime $activedatetime
     * @return DopDogovor
     */
    public function setActivedatetime($activedatetime)
    {
        $this->activedatetime = $activedatetime;
    
        return $this;
    }

    /**
     * Get activedatetime
     *
     * @return \DateTime 
     */
    public function getActivedatetime()
    {
        return $this->activedatetime;
    }

    /**
     * Set subject
     *
     * @param string $subject
     * @return DopDogovor
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    
        return $this;
    }

    /**
     * Get subject
     *
     * @return string 
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return DopDogovor
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    
        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set filepath
     *
     * @param string $filepath
     * @return DopDogovor
     */
    public function setFilepath($filepath)
    {
        $this->filepath = $filepath;
    
        return $this;
    }

    /**
     * Get filepath
     *
     * @return string 
     */
    public function getFilepath()
    {
        return $this->filepath;
    }

    /**
     * Set total
     *
     * @param float $total
     * @return DopDogovor
     */
    public function setTotal($total)
    {
        $this->total = $total;
    
        return $this;
    }

    /**
     * Get total
     *
     * @return float 
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set dogovor
     *
     * @param \Lists\DogovorBundle\Entity\Dogovor $dogovor
     * @return DopDogovor
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
     * Set stuff
     *
     * @param \SD\UserBundle\Entity\Staff $stuff
     * @return DopDogovor
     */
    public function setStuff(\SD\UserBundle\Entity\Staff $stuff = null)
    {
        $this->stuff = $stuff;
    
        return $this;
    }

    /**
     * Get stuff
     *
     * @return \SD\UserBundle\Entity\Staff 
     */
    public function getStuff()
    {
        return $this->stuff;
    }

    /**
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     * @return DopDogovor
     */
    public function setUser(\SD\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \SD\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}