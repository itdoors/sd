<?php

namespace Lists\OrganizationBundle\Form;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class OrganizationSalesFormType
 */
class OrganizationSalesFormType extends AbstractType
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
        /** @var \Lists\LookupBundle\Entity\LookupRepository $lr */
        $lr = $this->container->get('lists_lookup.repository');

        $builder
            ->add('name', 'text', array(
                'required' => true
            ))
            ->add('address')
            ->add('mailingAddress')
            ->add('ownership', 'entity', array(
                'class'=>'Lists\OrganizationBundle\Entity\OrganizationOwnership',
                'property'=>'shortName',
                'empty_value' => ''
            ))
            ->add('organizationType', 'entity', array(
                'class'=>'Lists\OrganizationBundle\Entity\OrganizationType',
                'property'=>'title',
                'empty_value' => ''
            ))
            ->add('group', 'entity', array(
                'class'=>'Lists\OrganizationBundle\Entity\OrganizationGroup',
                'property'=>'name',
                'empty_value' => ''
            ))
            ->add('city', 'entity', array(
                'class'=>'Lists\CityBundle\Entity\City',
                'property'=>'name',
                'empty_value' => ''
            ))
            ->add('edrpou')
            ->add('inn')
            ->add('certificate')
            ->add('shortname')
            ->add('site')
            ->add('scope', 'entity', array(
                'class'=>'Lists\LookupBundle\Entity\Lookup',
                'property'=>'name',
                'empty_value' => '',
                'query_builder' => $lr->getOnlyScopeQuery()
            ))
            ->add('organizationsigns', 'entity', array(
                'class'=>'Lists\LookupBundle\Entity\Lookup',
                'property'=>'name',
                'empty_value' => '',
                'multiple' => 'multiple',
                'query_builder' => $lr->getGroupOrganizationQuery()
            ))
            ->add('phone')
            ->add('physicalAddress');
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
            'validation_groups' => array('new'),
            'translation_domain' => 'ListsOrganizationBundle'
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
