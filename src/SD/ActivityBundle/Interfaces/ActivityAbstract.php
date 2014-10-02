<?php

namespace SD\ActivityBundle\Interfaces;

/**
 * ActivityAbstract class
 */
abstract class ActivityAbstract
{
    public $numberShowLastDays = 7;
    /**
     * @return bool
     */
    abstract public function getActivity();
}
