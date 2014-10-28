<?php

namespace Lists\DepartmentBundle\Form;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class DepartmentForm
 */
class DepartmentForm extends AbstractType
{
    /**
     * @var Container $container
     */
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
            ->add('name', 'text')
            ->add('city', 'entity', array(
                'class'=>'Lists\CityBundle\Entity\City',
                'property'=>'name',
                'empty_value' => ''
            ))
            ->add('address', 'text')
            ->add('mpk', 'text')
            ->add('type', 'entity', array(
                'class'=>'Lists\DepartmentBundle\Entity\DepartmentsType',
                'property'=>'name',
                'empty_value' => '',
                'required' => false
            ))
            ->add('statusDate', 'text', array(
                'required' => false
            ))
            ->add('description', 'textarea', array(
                'required' => false
            ))
            ->add('status', 'entity', array(
                'class'=>'Lists\DepartmentBundle\Entity\DepartmentsStatus',
                'property'=>'name',
                'empty_value' => '',
                'required' => false
            ))
            ->add('opermanager', 'entity', array(
                'class'=>'SD\UserBundle\Entity\User',
                'empty_value' => '',
                'required' => false
            ))
            ->add('create', 'submit')
            ->add('cancel', 'submit');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Lists\DepartmentBundle\Entity\Departments',
            'translation_domain' => 'ListsDepartmentBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'departmentForm';
    }
}
