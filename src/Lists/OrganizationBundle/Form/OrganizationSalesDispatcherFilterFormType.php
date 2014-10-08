<?php

namespace Lists\OrganizationBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class OrganizationSalesDispatcherFilterFormType
 */
class OrganizationSalesDispatcherFilterFormType extends OrganizationSalesFilterFormType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'organizationSalesDispatcherFilterForm';
    }
}
