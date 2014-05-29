<?php

namespace Lists\OrganizationBundle\Form;

/**
 * Class OrganizationSalesAdminFilterFormType
 */
class OrganizationSalesAdminFilterFormType extends OrganizationSalesDispatcherFilterFormType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'organizationSalesAdminFilterForm';
    }
}
