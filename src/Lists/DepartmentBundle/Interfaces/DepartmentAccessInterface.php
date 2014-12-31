<?php

namespace Lists\DepartmentBundle\Interfaces;

/**
 * DepartmentAccessInterface interface
 */
interface DepartmentAccessInterface
{
    /**
     * @return bool
     */
    public function canUse();
}
