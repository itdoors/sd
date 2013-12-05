<?php

namespace Lists\OrganizationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OrganizationSalesFormType extends AbstractType
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var \Lists\LookupBundle\Entity\LookupRepository $lr */
        $lr = $this->container->get('lists_lookup.repository');

        $builder
            ->add('name')
            ->add('address')
            ->add('mailingAddress')
            ->add('organizationType', 'entity', array(
                'class'=>'Lists\OrganizationBundle\Entity\OrganizationType',
                'property'=>'title'
            ))
            ->add('city', 'entity', array(
                'class'=>'Lists\CityBundle\Entity\City',
                'property'=>'name'
            ))
            ->add('scope', 'entity', array(
                'class'=>'Lists\LookupBundle\Entity\Lookup',
                'property'=>'name'
            ))
            ->add('edrpou')
            ->add('inn')
            ->add('certificate')
            //->add('shortDescription')
            ->add('shortname')
            ->add('site')
            ->add('city', 'entity', array(
                'class'=>'Lists\CityBundle\Entity\City',
                'property'=>'name',
                //'query_builder' =>
            ))
            ->add('scope', 'entity', array(
                'class'=>'Lists\LookupBundle\Entity\Lookup',
                'property'=>'name',
                'query_builder' => $lr->getOnlyScopeQuery()
            ))
        ;

        $builder
            ->add('save', 'submit')
            ->add('cancel', 'submit');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Lists\OrganizationBundle\Entity\Organization',
            'validation_groups' => array('new')
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'organizationSalesForm';
    }
}
