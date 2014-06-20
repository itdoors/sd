<?php

namespace Lists\OrganizationBundle\Form;

use Lists\OrganizationBundle\Form\OrganizationSalesFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class OrganizationDogovotAdminFormType
 */
class OrganizationDogovorAdminFormType extends OrganizationSalesFormType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        /** @var \Lists\LookupBundle\Entity\LookupRepository $lr */
        $lr = $this->container->get('lists_lookup.repository');

        $builder->add('lookup', 'entity', array(
            'class' => 'Lists\LookupBundle\Entity\Lookup',
            'property' => 'name',
            'query_builder' => $lr->getLookups()
        ));
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
        return 'organizationDogovorAdminForm';
    }
}
