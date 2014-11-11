<?php

namespace SD\DashboardBundle\Interfaces;

/**
 * OrganizationAccessInterface interface
 */
interface DashboardAccessInterface
{
    /**
     * @return bool
     */
    public function canSeeMyActions();
}
