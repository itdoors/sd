<?php

namespace Lists\DogovorBundle\Interfaces;

/**
 * DogovorAccessInterface interface
 */
interface DogovorAccessInterface
{
    /**
     * @return bool
     */
    public function canSee();
    /**
     * @return bool
     */
    public function canEdit();
    /**
     * @return bool
     */
    public function canSeeList();
    /**
     * @return bool
     */
    public function canSeeDanger();
    /**
     * @return bool
     */
    public function canProlongate();
    /**
     * @return bool
     */
    public function canAddDogovor();
    /**
     * @return bool
     */
    public function canAddDopDogovor();
}
