<?php

namespace Lists\OrganizationBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;

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
