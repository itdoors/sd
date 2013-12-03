<?php

namespace Lists\HandlingBundle\Form;

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
