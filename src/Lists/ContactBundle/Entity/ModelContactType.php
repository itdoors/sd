<?php

namespace Lists\ContactBundle\Entity;

/**
 * ModelContactType
 */
class ModelContactType
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
    private $slug;

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
     * @return ModelContactType
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
     * Set slug
     *
     * @param string $slug
     *
     * @return ModelContactType
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * __toString
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
    private $contacts;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contacts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add contacts
     *
     * @param \Lists\ContactBundle\Entity\ModelContact $contacts
     *
     * @return ModelContactType
     */
    public function addContact(\Lists\ContactBundle\Entity\ModelContact $contacts)
    {
        $this->contacts[] = $contacts;

        return $this;
    }

    /**
     * Remove contacts
     *
     * @param \Lists\ContactBundle\Entity\ModelContact $contacts
     */
    public function removeContact(\Lists\ContactBundle\Entity\ModelContact $contacts)
    {
        $this->contacts->removeElement($contacts);
    }

    /**
     * Get contacts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContacts()
    {
        return $this->contacts;
    }
}