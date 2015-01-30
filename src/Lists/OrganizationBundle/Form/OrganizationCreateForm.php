<?php

namespace Lists\OrganizationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class OrganizationCreateForm
 */
class OrganizationCreateForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'required' => true
            ))
            ->add('shortname', 'text', array(
                'required' => true
            ))
            ->add('edrpou', 'text', array(
                'required' => true
            ))
            
            ->add('submit', 'submit')
            ->add('cancel', 'submit');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Lists\OrganizationBundle\Entity\Organization',
            'validation_groups' => array('create'),
            'translation_domain' => 'ListsOrganizationBundle',
            'csrf_protection'   => false
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'organizationCreateForm';
    }
}
