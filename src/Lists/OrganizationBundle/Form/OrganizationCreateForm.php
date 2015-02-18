<?php

namespace Lists\OrganizationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class OrganizationCreateForm
 */
class OrganizationCreateForm extends AbstractType
{
    protected $container;
    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }
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
            ->add('contact', new \Lists\ContactBundle\Form\ModelContactOrganizationFormType($this->container), array(
                'mapped' => false
            ))
            
            ->add('submit', 'submit')
            ->add('cancel', 'submit');
        $builder->get('contact')->remove('add');
        $builder->get('contact')->remove('cancel');
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
            'csrf_protection'   => false,
            'cascade_validation' => true
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
