<?php

namespace Lists\OrganizationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OrganizationFilterFormType extends AbstractType
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
            ->add('city', 'entity', array('class'=>'Lists\CityBundle\Entity\City', 'property'=>'name'))
            ->add('scope', 'entity', array('class'=>'Lists\LookupBundle\Entity\Lookup', 'property'=>'name'))
            ->add('users', 'entity', array('class'=>'SD\UserBundle\Entity\User', 'mapped' => false, 'multiple' => true))
        ;

        $builder
            ->add('save', 'submit');
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Lists\OrganizationBundle\Entity\Organization',
            'validation_groups' => false
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'organizationFilterForm';
    }
}
