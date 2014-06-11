<?php

namespace Lists\OrganizationBundle\Form;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class OrganizationSalesFilterFormType
 */
class OrganizationSalesFilterFormType extends AbstractType
{
    protected $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;

        /** @var \Lists\LookupBundle\Entity\LookupRepository $lr */
        $this->lr = $this->container->get('lists_lookup.repository');

        /** @var \SD\UserBundle\Entity\UserRepository $ur */
        $this->ur = $this->container->get('sd_user.repository');
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('city', 'entity', array(
                'class'=>'Lists\CityBundle\Entity\City',
                'multiple' => true,
                'mapped' => false,
                'property'=>'name'
            ))
            ->add('scope', 'entity', array(
                'class'=>'Lists\LookupBundle\Entity\Lookup',
                'property'=>'name',
                'mapped' => false,
                'multiple' => true,
                'query_builder' => $this->lr->getOnlyScopeQuery()
            ))
            ->add('users', 'entity', array(
                'class'=>'SD\UserBundle\Entity\User',
                'mapped' => false,
                'multiple' => true,
                'property'=>'fullname',
                'query_builder' => $this->ur->getOnlyStaff()
            ));

        $builder
            ->add('save', 'submit')
            ->add('reset', 'submit');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Lists\OrganizationBundle\Entity\Organization',
            'validation_groups' => false,
            'csrf_protection' => false,
            'translation_domain' => 'ListsOrganizationBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'organizationSalesFilterForm';
    }
}
