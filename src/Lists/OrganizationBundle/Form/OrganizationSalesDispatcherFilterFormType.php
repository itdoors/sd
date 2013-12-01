<?php

namespace Lists\OrganizationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OrganizationSalesDispatcherFilterFormType extends OrganizationSalesFilterFormType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('organization', 'hidden', array(
                'mapped' => false
            ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'organizationSalesDispatcherFilterForm';
    }
}