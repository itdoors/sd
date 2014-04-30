<?php

namespace Lists\OrganizationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OrganizationType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mpk')
            ->add('name')
            ->add('address')
            ->add('contacts')
            ->add('shortname')
            ->add('isSmeta')
            ->add('mailingAddress')
            ->add('rs')
            ->add('edrpou')
            ->add('inn')
            ->add('certificate')
            ->add('shortDescription')
            ->add('site')
            ->add('organization_type_id')
            ->add('city_id')
            ->add('scope_id')
            ->add('createdatetime')
            ->add('physicalAddress')
            ->add('phone')
            ->add('group_id')
            ->add('parent_id')
            ->add('city')
            ->add('scope')
            ->add('organizationType')
            ->add('group')
            ->add('creator')
            ->add('parent')
            ->add('users')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Lists\OrganizationBundle\Entity\Organization'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'lists_organizationbundle_organization';
    }
}
