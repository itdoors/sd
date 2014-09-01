<?php

namespace Lists\CompanystructureBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use ITDoors\ControllingBundle\Entity\InvoiceCompanystructure;

/**
 * Companystructure
 * 
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="companystructure")
 * @ORM\Entity(repositoryClass="Lists\CompanystructureBundle\Entity\CompanystructureRepository")
 * 
 */
class Companystructure
{
    /**
     * @ORM\Column(type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $mpk;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=12, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(name="stuff_id", type="bigint", nullable=true)
     */
    private $stuffId;

    /**
     * @ORM\OneToMany(targetEntity="ITDoors\ControllingBundle\Entity\InvoiceCompanystructure", mappedBy="companystructure")
     */
    private $invoicecompanystructure;

    /**
     * @ORM\ManyToOne(targetEntity="SD\UserBundle\Entity\Stuff", inversedBy=null)
     */
    private $stuff;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Companystructure", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;
    /**
     * @ORM\OneToMany(targetEntity="Companystructure", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;
    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="Lists\RegionBundle\Entity\Region",  inversedBy="companystructure")
     */
    private $region;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->invoicecompanystructure = new \Doctrine\Common\Collections\ArrayCollection();
        $this->region = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * @return Companystructure
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
        return  $this->name;
    }
    /**
     * Get name
     *
     * @return string 
     */
    public function getNameForList()
    {
        return str_repeat('&nbsp;&nbsp;&nbsp;', $this->lvl).$this->name;
    }

    /**
     * Set mpk
     *
     * @param string $mpk
     * 
     * @return Companystructure
     */
    public function setMpk($mpk)
    {
        $this->mpk = $mpk;

        return $this;
    }

    /**
     * Get mpk
     *
     * @return string 
     */
    public function getMpk()
    {
        return $this->mpk;
    }

    /**
     * Set address
     *
     * @param string $address
     * 
     * @return Companystructure
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * 
     * @return Companystructure
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set stuffId
     *
     * @param integer $stuffId
     * 
     * @return Companystructure
     */
    public function setStuffId($stuffId)
    {
        $this->stuffId = $stuffId;

        return $this;
    }

    /**
     * Get stuffId
     *
     * @return integer 
     */
    public function getStuffId()
    {
        return $this->stuffId;
    }

    /**
     * Add invoicecompanystructure
     *
     * @param InvoiceCompanystructure $invoicecompanystructure
     * 
     * @return Companystructure
     */
    public function addInvoicecompanystructure(InvoiceCompanystructure $invoicecompanystructure)
    {
        $this->invoicecompanystructure[] = $invoicecompanystructure;

        return $this;
    }

    /**
     * Remove invoicecompanystructure
     *
     * @param InvoiceCompanystructure $invoicecompanystructure
     */
    public function removeInvoicecompanystructure(InvoiceCompanystructure $invoicecompanystructure)
    {
        $this->invoicecompanystructure->removeElement($invoicecompanystructure);
    }

    /**
     * Get invoicecompanystructure
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getInvoicecompanystructure()
    {
        return $this->invoicecompanystructure;
    }

    /**
     * Set stuff
     *
     * @param \SD\UserBundle\Entity\Stuff $stuff
     * 
     * @return Companystructure
     */
    public function setStuff(\SD\UserBundle\Entity\Stuff $stuff = null)
    {
        $this->stuff = $stuff;

        return $this;
    }

    /**
     * Get stuff
     *
     * @return \SD\UserBundle\Entity\Stuff 
     */
    public function getStuff()
    {
        return $this->stuff;
    }

    /**
     * Set parent
     *
     * @param \Lists\CompanystructureBundle\Entity\Companystructure $parent
     * 
     * @return Companystructure
     */
    public function setParent(\Lists\CompanystructureBundle\Entity\Companystructure $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Lists\CompanystructureBundle\Entity\Companystructure 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add region
     *
     * @param \Lists\RegionBundle\Entity\Region $region
     * 
     * @return Companystructure
     */
    public function addRegion(\Lists\RegionBundle\Entity\Region $region)
    {
        $this->region[] = $region;

        return $this;
    }

    /**
     * Remove region
     *
     * @param \Lists\RegionBundle\Entity\Region $region
     */
    public function removeRegion(\Lists\RegionBundle\Entity\Region $region)
    {
        $this->region->removeElement($region);
    }

    /**
     * Get region
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * __toStrong
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $userstuff;


    /**
     * Add userstuff
     *
     * @param \SD\UserBundle\Entity\Stuff $userstuff
     * 
     * @return Companystructure
     */
    public function addUserstuff(\SD\UserBundle\Entity\Stuff $userstuff)
    {
        $this->userstuff[] = $userstuff;

        return $this;
    }

    /**
     * Remove userstuff
     *
     * @param \SD\UserBundle\Entity\Stuff $userstuff
     */
    public function removeUserstuff(\SD\UserBundle\Entity\Stuff $userstuff)
    {
        $this->userstuff->removeElement($userstuff);
    }

    /**
     * Get userstuff
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUserstuff()
    {
        return $this->userstuff;
    }
    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer")
     */
    private $lft;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer")
     */
    private $lvl;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer")
     */
    private $rgt;

    /**
     * @Gedmo\TreeRoot
     * @ORM\Column(name="root", type="integer", nullable=true)
     */
    private $root;

}