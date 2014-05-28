<?php

namespace Lists\DogovorBundle\Entity;

/**
 * DogovorHandling
 */
class DogovorHandling
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Lists\DogovorBundle\Entity\Dogovor
     */
    private $dogovor;

    /**
     * @var \Lists\HandlingBundle\Entity\Handling
     */
    private $handling;

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
     * Set dogovor
     *
     * @param \Lists\DogovorBundle\Entity\Dogovor $dogovor
     *
     * @return DogovorHandling
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
     * Set handling
     *
     * @param \Lists\HandlingBundle\Entity\Handling $handling
     *
     * @return DogovorHandling
     */
    public function setHandling(\Lists\HandlingBundle\Entity\Handling $handling = null)
    {
        $this->handling = $handling;

        return $this;
    }

    /**
     * Get handling
     *
     * @return \Lists\HandlingBundle\Entity\Handling
     */
    public function getHandling()
    {
        return $this->handling;
    }
}
