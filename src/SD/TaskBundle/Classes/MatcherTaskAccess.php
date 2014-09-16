<?php

namespace SD\TaskBundle\Classes;

/**
 * MatcherTaskAccess class
 */
class MatcherTaskAccess extends BasicTaskAccess
{
    /**
     * @return bool
     */
    public function canSignUp()
    {
        if ($this->getStage() == 'matching' && $this->getIsViewed()) {

            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function canRefuseSignUp()
    {
        if ($this->getStage() == 'matching' && $this->getIsViewed()) {

            return true;
        }
        return false;
    }
}
