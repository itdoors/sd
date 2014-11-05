<?php

namespace Lists\HandlingBundle\Form;

/**
 * Class HandlingSalesAdminFilterFormType
 */
class HandlingSalesAdminFilterFormType extends HandlingSalesDispatcherFilterFormType
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'handlingSalesAdminFilterForm';
    }
}
