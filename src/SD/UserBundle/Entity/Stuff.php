<?php

namespace SD\UserBundle\Entity;

/**
 * Stuff
 */
class Stuff
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $phoneInside;

    /**
     * @var string
     */
    private $mobilephone;

    /**
     * @var string
     */
    private $phonePersonal;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $stuffclass;

    /**
     * @var string
     */
    private $issues;

    /**
     * @var string
     */
    private $birthPlace;

    /**
     * @var \DateTime
     */
    private $dateFire;

    /**
     * @var \DateTime
     */
    private $dateHire;

    /**
     * @var string
     */
    private $education;

    /**
     * @var \SD\UserBundle\Entity\User
     */
    private $user;

    /**
     * @var \Lists\CompanystructureBundle\Entity\Companystructure
     */
    private $companystructure;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId ()
    {
        return $this->id;
    }
    /**
     * Set phoneInside
     *
     * @param string $phoneInside
     * 
     * @return Stuff
     */
    public function setPhoneInside ($phoneInside)
    {
        $this->phoneInside = $phoneInside;

        return $this;
    }
    /**
     * Get phoneInside
     *
     * @return string 
     */
    public function getPhoneInside ()
    {
        return $this->phoneInside;
    }
    /**
     * Set mobilephone
     *
     * @param string $mobilephone
     * 
     * @return Stuff
     */
    public function setMobilephone ($mobilephone)
    {
        $this->mobilephone = $mobilephone;

        return $this;
    }
    /**
     * Get mobilephone
     *
     * @return string 
     */
    public function getMobilephone ()
    {
        return $this->mobilephone;
    }
    /**
     * Set phonePersonal
     *
     * @param string $phonePersonal
     * 
     * @return Stuff
     */
    public function setPhonePersonal ($phonePersonal)
    {
        $this->phonePersonal = $phonePersonal;

        return $this;
    }
    /**
     * Get phonePersonal
     *
     * @return string 
     */
    public function getPhonePersonal ()
    {
        return $this->phonePersonal;
    }
    /**
     * Set description
     *
     * @param string $description
     * 
     * @return Stuff
     */
    public function setDescription ($description)
    {
        $this->description = $description;

        return $this;
    }
    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription ()
    {
        return $this->description;
    }
    /**
     * Set stuffclass
     *
     * @param string $stuffclass
     * 
     * @return Stuff
     */
    public function setStuffclass ($stuffclass)
    {
        $this->stuffclass = $stuffclass;

        return $this;
    }
    /**
     * Get stuffclass
     *
     * @return string 
     */
    public function getStuffclass ()
    {
        return $this->stuffclass;
    }
    /**
     * Set issues
     *
     * @param string $issues
     * 
     * @return Stuff
     */
    public function setIssues ($issues)
    {
        $this->issues = $issues;

        return $this;
    }
    /**
     * Get issues
     *
     * @return string 
     */
    public function getIssues ()
    {
        return $this->issues;
    }
    /**
     * Set birthPlace
     *
     * @param string $birthPlace
     * 
     * @return Stuff
     */
    public function setBirthPlace ($birthPlace)
    {
        $this->birthPlace = $birthPlace;

        return $this;
    }
    /**
     * Get birthPlace
     *
     * @return string 
     */
    public function getBirthPlace ()
    {
        return $this->birthPlace;
    }
    /**
     * Set dateFire
     *
     * @param \DateTime $dateFire
     * 
     * @return Stuff
     */
    public function setDateFire ($dateFire)
    {
        $this->dateFire = $dateFire;

        return $this;
    }
    /**
     * Get dateFire
     *
     * @return \DateTime 
     */
    public function getDateFire ()
    {
        return $this->dateFire;
    }
    /**
     * Set dateHire
     *
     * @param \DateTime $dateHire
     * 
     * @return Stuff
     */
    public function setDateHire ($dateHire)
    {
        $this->dateHire = $dateHire;

        return $this;
    }
    /**
     * Get dateHire
     *
     * @return \DateTime 
     */
    public function getDateHire ()
    {
        return $this->dateHire;
    }
    /**
     * Set education
     *
     * @param string $education
     * 
     * @return Stuff
     */
    public function setEducation ($education)
    {
        $this->education = $education;

        return $this;
    }
    /**
     * Get education
     *
     * @return string 
     */
    public function getEducation ()
    {
        return $this->education;
    }
    /**
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     * 
     * @return Stuff
     */
    public function setUser (\SD\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }
    /**
     * Get user
     *
     * @return \SD\UserBundle\Entity\User 
     */
    public function getUser ()
    {
        return $this->user;
    }
    /**
     * Set companystructure
     *
     * @param \Lists\CompanystructureBundle\Entity\Companystructure $companystructure
     * 
     * @return Stuff
     */
    public function setCompanystructure (\Lists\CompanystructureBundle\Entity\Companystructure $companystructure = null)
    {
        $this->companystructure = $companystructure;

        return $this;
    }
    /**
     * Get companystructure
     *
     * @return \Lists\CompanystructureBundle\Entity\Companystructure 
     */
    public function getCompanystructure ()
    {
        return $this->companystructure;
    }

    /**
     * @var integer
     */
    private $companystructureId;

    /**
     * Set companystructureId
     *
     * @param integer $companystructureId
     * 
     * @return Stuff
     */
    public function setCompanystructureId ($companystructureId)
    {
        $this->companystructureId = $companystructureId;

        return $this;
    }
    /**
     * Get companystructureId
     *
     * @return integer 
     */
    public function getCompanystructureId ()
    {
        return $this->companystructureId;
    }
    /**
     * ToString magic method
     *
     * @return string
     */
    public function __toString ()
    {
        return (string) $this->getId();
    }
}
