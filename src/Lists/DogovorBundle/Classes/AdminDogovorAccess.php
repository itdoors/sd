<?php

namespace Lists\DogovorBundle\Classes;

/**
 * AdminDogovorAccess class
 */
class AdminDogovorAccess extends BasicDogovorAccess
{
    /**
     * @return bool
     */
    public function canSee ()
    {
        return true;
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
        return true;
    }
    /**
     * @return bool
     */
    public function canSeeDanger ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canProlongate ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canAddDogovor ()
    {
        return true;
    }
    /**
     * @return bool
     */
    public function canAddDopDogovor ()
    {
        return true;
    }
}
