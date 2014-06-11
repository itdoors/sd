<?php

namespace Lists\DogovorBundle\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Dogovor
 */
class Dogovor
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \DateTime
     */
    private $startdatetime;

    /**
     * @var \DateTime
     */
    private $stopdatetime;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $filepath;

    /**
     * @var boolean
     */
    private $isActive;

    /**
     * @var string
     */
    private $mashtab;

    /**
     * @var boolean
     */
    private $prolongation;

    /**
     * @var string
     */
    private $number;

    /**
     * @var float
     */
    private $total;

    /**
     * @var string
     */
    private $maturity;

    /**
     * @var boolean
     */
    private $completionNotice;

    /**
     * @var integer
     */
    private $paymentDeferment;

    /**
     * @var string
     */
    private $prolongationTerm;

    /**
     * @var \DateTime
     */
    private $launchDate;

    /**
     * @var float
     */
    private $summMonthVat;

    /**
     * @var float
     */
    private $plannedPf1;

    /**
     * @var float
     */
    private $plannedPf1Percent;

    /**
     * @var \Lists\CityBundle\Entity\City
     */
    private $city;

    /**
     * @var \Lists\LookupBundle\Entity\Lookup
     */
    private $companyRole;

    /**
     * @var \Lists\CompanystructureBundle\Entity\Companystructure
     */
    private $companystructure;

    /**
     * @var \Lists\LookupBundle\Entity\Lookup
     */
    private $dogovorType;

    /**
     * @var \Lists\OrganizationBundle\Entity\Organization
     */
    private $organization;

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
     * Set name
     *
     * @param string $name
     *
     * @return Dogovor
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set startdatetime
     *
     * @param \DateTime $startdatetime
     *
     * @return Dogovor
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
     * Set stopdatetime
     *
     * @param \DateTime $stopdatetime
     *
     * @return Dogovor
     */
    public function setStopdatetime($stopdatetime)
    {
        $this->stopdatetime = $stopdatetime;

        return $this;
    }

    /**
     * Get stopdatetime
     *
     * @return \DateTime
     */
    public function getStopdatetime()
    {
        return $this->stopdatetime;
    }

    /**
     * Set subject
     *
     * @param string $subject
     *
     * @return Dogovor
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
     * Set filepath
     *
     * @param string $filepath
     *
     * @return Dogovor
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
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Dogovor
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
     * Set mashtab
     *
     * @param string $mashtab
     *
     * @return Dogovor
     */
    public function setMashtab($mashtab)
    {
        $this->mashtab = $mashtab;

        return $this;
    }

    /**
     * Get mashtab
     *
     * @return string
     */
    public function getMashtab()
    {
        return $this->mashtab;
    }

    /**
     * Set prolongation
     *
     * @param boolean $prolongation
     *
     * @return Dogovor
     */
    public function setProlongation($prolongation)
    {
        $this->prolongation = $prolongation;

        return $this;
    }

    /**
     * Get prolongation
     *
     * @return boolean
     */
    public function getProlongation()
    {
        return $this->prolongation;
    }

    /**
     * Set number
     *
     * @param string $number
     *
     * @return Dogovor
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
     * Set total
     *
     * @param float $total
     *
     * @return Dogovor
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
     * Set maturity
     *
     * @param string $maturity
     *
     * @return Dogovor
     */
    public function setMaturity($maturity)
    {
        $this->maturity = $maturity;

        return $this;
    }

    /**
     * Get maturity
     *
     * @return string
     */
    public function getMaturity()
    {
        return $this->maturity;
    }

    /**
     * Set completionNotice
     *
     * @param boolean $completionNotice
     *
     * @return Dogovor
     */
    public function setCompletionNotice($completionNotice)
    {
        $this->completionNotice = $completionNotice;

        return $this;
    }

    /**
     * Get completionNotice
     *
     * @return boolean
     */
    public function getCompletionNotice()
    {
        return $this->completionNotice;
    }

    /**
     * Set paymentDeferment
     *
     * @param integer $paymentDeferment
     *
     * @return Dogovor
     */
    public function setPaymentDeferment($paymentDeferment)
    {
        $this->paymentDeferment = $paymentDeferment;

        return $this;
    }

    /**
     * Get paymentDeferment
     *
     * @return integer
     */
    public function getPaymentDeferment()
    {
        return $this->paymentDeferment;
    }

    /**
     * Set prolongationTerm
     *
     * @param string $prolongationTerm
     *
     * @return Dogovor
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
     * Set launchDate
     *
     * @param \DateTime $launchDate
     *
     * @return Dogovor
     */
    public function setLaunchDate($launchDate)
    {
        $this->launchDate = $launchDate;

        return $this;
    }

    /**
     * Get launchDate
     *
     * @return \DateTime
     */
    public function getLaunchDate()
    {
        return $this->launchDate;
    }

    /**
     * Set summMonthVat
     *
     * @param float $summMonthVat
     *
     * @return Dogovor
     */
    public function setSummMonthVat($summMonthVat)
    {
        $this->summMonthVat = $summMonthVat;

        return $this;
    }

    /**
     * Get summMonthVat
     *
     * @return float
     */
    public function getSummMonthVat()
    {
        return $this->summMonthVat;
    }

    /**
     * Set plannedPf1
     *
     * @param float $plannedPf1
     *
     * @return Dogovor
     */
    public function setPlannedPf1($plannedPf1)
    {
        $this->plannedPf1 = $plannedPf1;

        return $this;
    }

    /**
     * Get plannedPf1
     *
     * @return float
     */
    public function getPlannedPf1()
    {
        return $this->plannedPf1;
    }

    /**
     * Set plannedPf1Percent
     *
     * @param float $plannedPf1Percent
     *
     * @return Dogovor
     */
    public function setPlannedPf1Percent($plannedPf1Percent)
    {
        $this->plannedPf1Percent = $plannedPf1Percent;

        return $this;
    }

    /**
     * Get plannedPf1Percent
     *
     * @return float
     */
    public function getPlannedPf1Percent()
    {
        return $this->plannedPf1Percent;
    }

    /**
     * Set city
     *
     * @param \Lists\CityBundle\Entity\City $city
     *
     * @return Dogovor
     */
    public function setCity(\Lists\CityBundle\Entity\City $city = null)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return \Lists\CityBundle\Entity\City
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set companyRole
     *
     * @param \Lists\LookupBundle\Entity\Lookup $companyRole
     *
     * @return Dogovor
     */
    public function setCompanyRole(\Lists\LookupBundle\Entity\Lookup $companyRole = null)
    {
        $this->companyRole = $companyRole;

        return $this;
    }

    /**
     * Get companyRole
     *
     * @return \Lists\LookupBundle\Entity\Lookup
     */
    public function getCompanyRole()
    {
        return $this->companyRole;
    }

    /**
     * Set companystructure
     *
     * @param \Lists\CompanystructureBundle\Entity\Companystructure $companystructure
     *
     * @return Dogovor
     */
    public function setCompanystructure(\Lists\CompanystructureBundle\Entity\Companystructure $companystructure = null)
    {
        $this->companystructure = $companystructure;

        return $this;
    }

    /**
     * Get companystructure
     *
     * @return \Lists\CompanystructureBundle\Entity\Companystructure
     */
    public function getCompanystructure()
    {
        return $this->companystructure;
    }

    /**
     * Set dogovorType
     *
     * @param \Lists\LookupBundle\Entity\Lookup $dogovorType
     *
     * @return Dogovor
     */
    public function setDogovorType(\Lists\LookupBundle\Entity\Lookup $dogovorType = null)
    {
        $this->dogovorType = $dogovorType;

        return $this;
    }

    /**
     * Get dogovorType
     *
     * @return \Lists\LookupBundle\Entity\Lookup
     */
    public function getDogovorType()
    {
        return $this->dogovorType;
    }

    /**
     * Set organization
     *
     * @param \Lists\OrganizationBundle\Entity\Organization $organization
     *
     * @return Dogovor
     */
    public function setOrganization(\Lists\OrganizationBundle\Entity\Organization $organization = null)
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * Get organization
     *
     * @return \Lists\OrganizationBundle\Entity\Organization
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * Set stuff
     *
     * @param \SD\UserBundle\Entity\Staff $stuff
     *
     * @return Dogovor
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
     *
     * @return Dogovor
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
     * @var \DateTime
     */
    private $prolongationDate;

    /**
     * @var \Lists\OrganizationBundle\Entity\Organization
     */
    private $customer;

    /**
     * @var \Lists\OrganizationBundle\Entity\Organization
     */
    private $performer;

    /**
     * Set prolongationDate
     *
     * @param \DateTime $prolongationDate
     *
     * @return Dogovor
     */
    public function setProlongationDate($prolongationDate)
    {
        $this->prolongationDate = $prolongationDate;

        return $this;
    }

    /**
     * Get prolongationDate
     *
     * @return \DateTime
     */
    public function getProlongationDate()
    {
        return $this->prolongationDate;
    }

    /**
     * Set customer
     *
     * @param \Lists\OrganizationBundle\Entity\Organization $customer
     *
     * @return Dogovor
     */
    public function setCustomer(\Lists\OrganizationBundle\Entity\Organization $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \Lists\OrganizationBundle\Entity\Organization
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set performer
     *
     * @param \Lists\OrganizationBundle\Entity\Organization $performer
     *
     * @return Dogovor
     */
    public function setPerformer(\Lists\OrganizationBundle\Entity\Organization $performer = null)
    {
        $this->performer = $performer;

        return $this;
    }

    /**
     * Get performer
     *
     * @return \Lists\OrganizationBundle\Entity\Organization
     */
    public function getPerformer()
    {
        return $this->performer;
    }
    /**
     * @var \SD\UserBundle\Entity\User
     */
    private $saller;

    /**
     * Set saller
     *
     * @param \SD\UserBundle\Entity\User $saller
     *
     * @return Dogovor
     */
    public function setSaller(\SD\UserBundle\Entity\User $saller = null)
    {
        $this->saller = $saller;

        return $this;
    }

    /**
     * Get saller
     *
     * @return \SD\UserBundle\Entity\User
     */
    public function getSaller()
    {
        return $this->saller;
    }

    /**
     * getAbsolutePath
     *
     * @return null|string
     */
    public function getAbsolutePath()
    {
        return null === $this->filepath
            ? null
            : $this->getUploadRootDir().'/'.$this->filepath;
    }

    /**
     * getWebPath
     *
     * @return null|string
     */
    public function getWebPath()
    {
        return null === $this->filepath
            ? null
            : $this->getUploadDir().'/'.$this->filepath;
    }

    /**
     * getUploadRootDir
     *
     * @return string
     */
    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    /**
     * getUploadDir
     *
     * @return string
     */
    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/dogovor';
    }

    private $file;

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * upload
     */
    public function upload()
    {
        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return;
        }

        // use the original file name here but you should
        // sanitize it at least to avoid any security issues

        // move takes the target directory and then the
        // target filename to move to

        $fileExtension = $this->getFile()->getClientOriginalExtension();

        $newFileName = md5(microtime());

        $filepath = $newFileName . '.' . $fileExtension;

        $uploadDir = $this->getUploadRootDir();

        $this->getFile()->move(
            $uploadDir,
            $filepath
        );

        // set the path property to the filename where you've saved the file
        $this->filepath = $filepath;

        // clean up the file property as you won't need it anymore
        $this->file = null;
    }
    /**
     * @var integer
     */
    private $customerId;

    /**
     * @var integer
     */
    private $performerId;

    /**
     * Set customerId
     *
     * @param integer $customerId
     *
     * @return Dogovor
     */
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;

        return $this;
    }

    /**
     * Get customerId
     *
     * @return integer
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * Set performerId
     *
     * @param integer $performerId
     *
     * @return Dogovor
     */
    public function setPerformerId($performerId)
    {
        $this->performerId = $performerId;

        return $this;
    }

    /**
     * Get performerId
     *
     * @return integer
     */
    public function getPerformerId()
    {
        return $this->performerId;
    }
    /**
     * @var integer
     */
    private $organizationId;

    /**
     * Set organizationId
     *
     * @param integer $organizationId
     *
     * @return Dogovor
     */
    public function setOrganizationId($organizationId)
    {
        $this->organizationId = $organizationId;

        return $this;
    }

    /**
     * Get organizationId
     *
     * @return integer
     */
    public function getOrganizationId()
    {
        return $this->organizationId;
    }

    /**
     * Sets startdatetime from startdatetimeString
     *
     * @param string $startdatetimeString
     */
    public function setStartdatetimeString($startdatetimeString)
    {
        if ($startdatetimeString) {
            $this->setStartdatetime(new \DateTime($startdatetimeString));
        }
    }

    /**
     * Sets stopdatetime from stopdatetimeString
     *
     * @param string $stopdatetimeString
     */
    public function setStopdatetimeString($stopdatetimeString)
    {
        if ($stopdatetimeString) {
            $this->setStopdatetime(new \DateTime($stopdatetimeString));
        }
    }
    /**
     * @var integer
     */
    private $cityId;

    /**
     * Set cityId
     *
     * @param integer $cityId
     *
     * @return Dogovor
     */
    public function setCityId($cityId)
    {
        $this->cityId = $cityId;

        return $this;
    }

    /**
     * Get cityId
     *
     * @return integer
     */
    public function getCityId()
    {
        return $this->cityId;
    }
    /**
     * @var integer
     */
    private $dogovorTypeId;

    /**
     * Set dogovorTypeId
     *
     * @param integer $dogovorTypeId
     *
     * @return Dogovor
     */
    public function setDogovorTypeId($dogovorTypeId)
    {
        $this->dogovorTypeId = $dogovorTypeId;

        return $this;
    }

    /**
     * Get dogovorTypeId
     *
     * @return integer
     */
    public function getDogovorTypeId()
    {
        return $this->dogovorTypeId;
    }
    
    /**
     * __toString()
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->getNumber();
    }
    /**
     * @var integer
     */
    private $dogovorId1c;


    /**
     * Set dogovorId1c
     *
     * @param integer $dogovorId1c
     * @return Dogovor
     */
    public function setDogovorId1c($dogovorId1c)
    {
        $this->dogovorId1c = $dogovorId1c;
    
        return $this;
    }

    /**
     * Get dogovorId1c
     *
     * @return integer 
     */
    public function getDogovorId1c()
    {
        return $this->dogovorId1c;
    }
    /**
     * @var string
     */
    private $dogovorGuid;


    /**
     * Set dogovorGuid
     *
     * @param string $dogovorGuid
     * @return Dogovor
     */
    public function setDogovorGuid($dogovorGuid)
    {
        $this->dogovorGuid = $dogovorGuid;
    
        return $this;
    }

    /**
     * Get dogovorGuid
     *
     * @return string 
     */
    public function getDogovorGuid()
    {
        return $this->dogovorGuid;
    }
}
