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
            if ($this->getLastCommitStage() != 'sign_up') {

                return true;
            }

        }

        return false;
    }

    /**
     * @return bool
     */
    public function canRefuseSignUp()
    {
        if ($this->getStage() == 'matching' && $this->getIsViewed()) {
            if ($this->getLastCommitStage() != 'sign_up') {

                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function canLeaveComment()
    {
        if ($this->canRefuseSignUp() || $this->canSignUp()) {

            return true;
        }

        return false;
    }
}
