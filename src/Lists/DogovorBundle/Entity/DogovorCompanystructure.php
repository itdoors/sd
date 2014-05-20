<?php

namespace Lists\DogovorBundle\Entity;

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
    private $companyStructureId;

    /**
     * @var \Lists\DogovorBundle\Entity\Dogovor
     */
    private $dogovors;

    /**
     * @var \Lists\CompanystructureBundle\Entity\Companystructure
     */
    private $companyStructures;


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
     * Set companyStructureId
     *
     * @param integer $companyStructureId
     * @return DogovorCompanystructure
     */
    public function setCompanyStructureId($companyStructureId)
    {
        $this->companyStructureId = $companyStructureId;
    
        return $this;
    }

    /**
     * Get companyStructureId
     *
     * @return integer 
     */
    public function getCompanyStructureId()
    {
        return $this->companyStructureId;
    }

    /**
     * Set dogovors
     *
     * @param \Lists\DogovorBundle\Entity\Dogovor $dogovors
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
     * Set companyStructures
     *
     * @param \Lists\CompanystructureBundle\Entity\Companystructure $companyStructures
     * @return DogovorCompanystructure
     */
    public function setCompanyStructures(\Lists\CompanystructureBundle\Entity\Companystructure $companyStructures = null)
    {
        $this->companyStructures = $companyStructures;
    
        return $this;
    }

    /**
     * Get companyStructures
     *
     * @return \Lists\CompanystructureBundle\Entity\Companystructure 
     */
    public function getCompanyStructures()
    {
        return $this->companyStructures;
    }
}