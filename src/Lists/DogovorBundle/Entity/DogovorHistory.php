<?php

namespace Lists\DogovorBundle\Entity;

/**
 * DogovorHistory
 */
class DogovorHistory
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $createdatetime;

    /**
     * @var string
     */
    private $prolongationTerm;

    /**
     * @var \DateTime
     */
    private $prolongationDateFrom;

    /**
     * @var \DateTime
     */
    private $prolongationDateTo;

    /**
     * @var integer
     */
    private $dogovorId;

    /**
     * @var integer
     */
    private $dopDogovorId;

    /**
     * @var \Lists\DogovorBundle\Entity\Dogovor
     */
    private $dogovor;

    /**
     * @var \Lists\DogovorBundle\Entity\DopDogovor
     */
    private $dopDogovor;

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
     * Set createdatetime
     *
     * @param \DateTime $createdatetime
     *
     * @return DogovorHistory
     */
    public function setCreatedatetime($createdatetime)
    {
        $this->createdatetime = $createdatetime;

        return $this;
    }

    /**
     * Get createdatetime
     *
     * @return \DateTime
     */
    public function getCreatedatetime()
    {
        return $this->createdatetime;
    }

    /**
     * Set prolongationTerm
     *
     * @param string $prolongationTerm
     *
     * @return DogovorHistory
     */
    public function setProlongationTerm($prolongationTerm)
    {
        $this->prolongationTerm = $prolongationTerm;

        return $this;
    }

    /**
     * Get prolongationTerm
     *
     * @return string
     */
    public function getProlongationTerm()
    {
        return $this->prolongationTerm;
    }

    /**
     * Set prolongationDateFrom
     *
     * @param \DateTime $prolongationDateFrom
     *
     * @return DogovorHistory
     */
    public function setProlongationDateFrom($prolongationDateFrom)
    {
        $this->prolongationDateFrom = $prolongationDateFrom;

        return $this;
    }

    /**
     * Get prolongationDateFrom
     *
     * @return \DateTime
     */
    public function getProlongationDateFrom()
    {
        return $this->prolongationDateFrom;
    }

    /**
     * Set prolongationDateTo
     *
     * @param \DateTime $prolongationDateTo
     *
     * @return DogovorHistory
     */
    public function setProlongationDateTo($prolongationDateTo)
    {
        $this->prolongationDateTo = $prolongationDateTo;

        return $this;
    }

    /**
     * Get prolongationDateTo
     *
     * @return \DateTime
     */
    public function getProlongationDateTo()
    {
        return $this->prolongationDateTo;
    }

    /**
     * Set dogovorId
     *
     * @param integer $dogovorId
     *
     * @return DogovorHistory
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
     * Set dopDogovorId
     *
     * @param integer $dopDogovorId
     *
     * @return DogovorHistory
     */
    public function setDopDogovorId($dopDogovorId)
    {
        $this->dopDogovorId = $dopDogovorId;

        return $this;
    }

    /**
     * Get dopDogovorId
     *
     * @return integer
     */
    public function getDopDogovorId()
    {
        return $this->dopDogovorId;
    }

    /**
     * Set dogovor
     *
     * @param \Lists\DogovorBundle\Entity\Dogovor $dogovor
     *
     * @return DogovorHistory
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
     * Set dopDogovor
     *
     * @param \Lists\DogovorBundle\Entity\DopDogovor $dopDogovor
     *
     * @return DogovorHistory
     */
    public function setDopDogovor(\Lists\DogovorBundle\Entity\DopDogovor $dopDogovor = null)
    {
        $this->dopDogovor = $dopDogovor;

        return $this;
    }

    /**
     * Get dopDogovor
     *
     * @return \Lists\DogovorBundle\Entity\DopDogovor
     */
    public function getDopDogovor()
    {
        return $this->dopDogovor;
    }

    /**
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     *
     * @return DogovorHistory
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
