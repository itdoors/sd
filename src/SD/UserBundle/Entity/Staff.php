<?php

namespace SD\UserBundle\Entity;

/**
 * Staff
 */
class Staff
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $mobilephone;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $stuffclass;

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
     * Set mobilephone
     *
     * @param string $mobilephone
     *
     * @return Staff
     */
    public function setMobilephone($mobilephone)
    {
        $this->mobilephone = $mobilephone;

        return $this;
    }

    /**
     * Get mobilephone
     *
     * @return string
     */
    public function getMobilephone()
    {
        return $this->mobilephone;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Staff
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set stuffclass
     *
     * @param string $stuffclass
     *
     * @return Staff
     */
    public function setStuffclass($stuffclass)
    {
        $this->stuffclass = $stuffclass;

        return $this;
    }

    /**
     * Get stuffclass
     *
     * @return string
     */
    public function getStuffclass()
    {
        return $this->stuffclass;
    }

    /**
     * Set user
     *
     * @param \SD\UserBundle\Entity\User $user
     *
     * @return Staff
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
    /**
     * @var \SD\UserBundle\Entity\Companystructure
     */
    private $companystructure;


    /**
     * Set companystructure
     *
     * @param \SD\UserBundle\Entity\Companystructure $companystructure
     * @return Staff
     */
    public function setCompanystructure(\SD\UserBundle\Entity\Companystructure $companystructure = null)
    {
        $this->companystructure = $companystructure;
    
        return $this;
    }

    /**
     * Get companystructure
     *
     * @return \SD\UserBundle\Entity\Companystructure 
     */
    public function getCompanystructure()
    {
        return $this->companystructure;
    }
    /**
     * @var string
     */
    private $issues;


    /**
     * Set issues
     *
     * @param string $issues
     * @return Staff
     */
    public function setIssues($issues)
    {
        $this->issues = $issues;
    
        return $this;
    }

    /**
     * Get issues
     *
     * @return string 
     */
    public function getIssues()
    {
        return $this->issues;
    }
    /**
     * @var string
     */
    private $phoneInside;

    /**
     * @var string
     */
    private $phonePersonal;


    /**
     * Set phoneInside
     *
     * @param string $phoneInside
     * @return Staff
     */
    public function setPhoneInside($phoneInside)
    {
        $this->phoneInside = $phoneInside;
    
        return $this;
    }

    /**
     * Get phoneInside
     *
     * @return string 
     */
    public function getPhoneInside()
    {
        return $this->phoneInside;
    }

    /**
     * Set phonePersonal
     *
     * @param string $phonePersonal
     * @return Staff
     */
    public function setPhonePersonal($phonePersonal)
    {
        $this->phonePersonal = $phonePersonal;
    
        return $this;
    }

    /**
     * Get phonePersonal
     *
     * @return string 
     */
    public function getPhonePersonal()
    {
        return $this->phonePersonal;
    }
    /**
     * @var string
     */
    private $birthPlase;


    /**
     * Set birthPlase
     *
     * @param string $birthPlase
     * @return Staff
     */
    public function setBirthPlase($birthPlase)
    {
        $this->birthPlase = $birthPlase;
    
        return $this;
    }

    /**
     * Get birthPlase
     *
     * @return string 
     */
    public function getBirthPlase()
    {
        return $this->birthPlase;
    }
    /**
     * @var string
     */
    private $birthPlace;


    /**
     * Set birthPlace
     *
     * @param string $birthPlace
     * @return Staff
     */
    public function setBirthPlace($birthPlace)
    {
        $this->birthPlace = $birthPlace;
    
        return $this;
    }

    /**
     * Get birthPlace
     *
     * @return string 
     */
    public function getBirthPlace()
    {
        return $this->birthPlace;
    }
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
     * Set dateFire
     *
     * @param \DateTime $dateFire
     * @return Staff
     */
    public function setDateFire($dateFire)
    {
        $this->dateFire = $dateFire;
    
        return $this;
    }

    /**
     * Get dateFire
     *
     * @return \DateTime 
     */
    public function getDateFire()
    {
        return $this->dateFire;
    }

    /**
     * Set dateHire
     *
     * @param \DateTime $dateHire
     * @return Staff
     */
    public function setDateHire($dateHire)
    {
        $this->dateHire = $dateHire;
    
        return $this;
    }

    /**
     * Get dateHire
     *
     * @return \DateTime 
     */
    public function getDateHire()
    {
        return $this->dateHire;
    }

    /**
     * Set education
     *
     * @param string $education
     * @return Staff
     */
    public function setEducation($education)
    {
        $this->education = $education;
    
        return $this;
    }

    /**
     * Get education
     *
     * @return string 
     */
    public function getEducation()
    {
        return $this->education;
    }
}
