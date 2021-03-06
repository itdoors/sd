<?php

namespace SD\TaskBundle\Classes;

/**
 * AuthorTaskAccess class
 */
class AuthorTaskAccess extends BasicTaskAccess
{

    /**
     * @return bool
     */
    public function canEditHeader()
    {
        if ($this->getStage() == 'matching') {

            return true;
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canEditDescription()
    {
        if ($this->getStage() == 'matching') {

            return true;
        }

        return false;
    }
    /**
     * @return bool
     */
    public function canEditEndDate()
    {
        if ($this->getStage() == 'matching') {

            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function canSetClosed()
    {
        if ($this->getStage() == 'matching') {

            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function canAddMatcher()
    {
        if ($this->getStage() == 'matching') {

            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function canReply()
    {
        if ($this->getStage() == 'matching') {

            return true;
        }

        return false;
    }
}
