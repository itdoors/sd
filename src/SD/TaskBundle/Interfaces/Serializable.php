<?php

namespace SD\TaskBundle\Interfaces;

/**
 * Serializable interface
 */
interface Serializable
{
    /**
     * @return bool
     */
    public function customSerialize();

}
