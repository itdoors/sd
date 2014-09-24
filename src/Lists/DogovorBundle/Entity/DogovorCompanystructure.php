<?php

namespace Lists\DogovorBundle\Entity;

use Lists\CompanystructureBundle\Entity\Companystructure;
use Doctrine\ORM\Mapping as ORM;

/**
 * DogovorCompanystructure
 */
class DogovorCompanystructure
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $dogovorId;

    /**
     * @var integer
     */
    private $companystructureId;

    /**
     * @var \Lists\DogovorBundle\Entity\Dogovor
     */
    private $dogovors;

    /**
     * @var \Lists\CompanystructureBundle\Entity\Companystructure
     */
    private $companystructures;

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
     * Set dogovorId
     *
     * @param integer $dogovorId
     * 
     * @return DogovorCompanystructure
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
     * Set companystructureId
     *
     * @param integer $companystructureId
     * 
     * @return DogovorCompanystructure
     */
    public function setCompanystructureId($companystructureId)
    {
        $this->companystructureId = $companystructureId;

        return $this;
    }

    /**
     * Get companyStructureId
     *
     * @return integer 
     */
    public function getCompanystructureId()
    {
        return $this->companystructureId;
    }

    /**
     * Set dogovors
     *
     * @param \Lists\DogovorBundle\Entity\Dogovor $dogovors
     * 
     * @return DogovorCompanystructure
     */
    public function setDogovors(\Lists\DogovorBundle\Entity\Dogovor $dogovors = null)
    {
        $this->dogovors = $dogovors;

        return $this;
    }

    /**
     * Get dogovors
     *
     * @return \Lists\DogovorBundle\Entity\Dogovor 
     */
    public function getDogovors()
    {
        return $this->dogovors;
    }

    /**
     * Set companystructures
     *
     * @param \Lists\CompanystructureBundle\Entity\Companystructure $companystructures
     * 
     * @return DogovorCompanystructure
     */
    public function setCompanystructures(Companystructure $companystructures = null)
    {
        $this->companystructures = $companystructures;

        return $this;
    }

    /**
     * Get companystructures
     *
     * @return \Lists\CompanystructureBundle\Entity\Companystructure 
     */
    public function getCompanystructures()
    {
        return $this->companystructures;
    }
}
