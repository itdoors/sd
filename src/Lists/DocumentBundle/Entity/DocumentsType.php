<?php

namespace Lists\DocumentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DocumentsType
 */
class DocumentsType
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
     * @var string
     */
    private $dockey;


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
     * @return DocumentsType
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
     * Set dockey
     *
     * @param string $dockey
     *
     * @return DocumentsType
     */
    public function setDockey($dockey)
    {
        $this->dockey = $dockey;

        return $this;
    }

    /**
     * Get dockey
     *
     * @return string 
     */
    public function getDockey()
    {
        return $this->dockey;
    }

    /**
     * toString menthod
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
