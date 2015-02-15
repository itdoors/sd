<?php

namespace Lists\DogovorBundle\Classes;

use Lists\DogovorBundle\Interfaces\DogovorAccessInterface;

/**
 * BasicDogovorAccess class
 */
class BasicDogovorAccess implements DogovorAccessInterface
{
    /**
     * @return bool
     */
    public function canSee ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canEdit ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canSeeList ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canSeeDanger ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canProlongate ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canAddDogovor ()
    {
        return false;
    }
    /**
     * @return bool
     */
    public function canAddDopDogovor ()
    {
        return false;
    }
}
