<?php

namespace SD\ActivityBundle\Interfaces;

/**
 * ActivityAbstract class
 */
abstract class ActivityAbstract
{
    public $numActivities = 10;
    /**
     * @return bool
     */
    abstract function getActivity();
}
