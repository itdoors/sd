<?php

namespace Lists\OrganizationBundle\Form;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class KvedFormType
 */
class KvedFormType extends AbstractType
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
        /** @var \Lists\LookupBundle\Entity\LookupRepository $lr */
//        $lr = $this->container->get('lists_lookup.repository');

        $builder
            ->add('kved', 'entity', array(
                'class'=>'Lists\OrganizationBundle\Entity\Kved',
                //'property'=>'name',
                'empty_value' => '',
                //'query_builder' => $lr->getOnlyScopeQuery()
            ))
            ->add('organizationId', 'hidden')
            ->add('add', 'submit');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null,
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
        return 'kvedForm';
    }
}
