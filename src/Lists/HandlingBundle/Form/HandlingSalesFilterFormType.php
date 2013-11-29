<?php

namespace Lists\HandlingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class HandlingSalesFilterFormType extends AbstractType
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

        /** @var \SD\UserBundle\Entity\UserRepository $ur */
        $ur = $this->container->get('sd_user.repository');

        $builder
            ->add('organization_id', 'hidden')
            ->add('city', 'entity', array(
                'class'=>'Lists\CityBundle\Entity\City',
                'mapped' => false,
                'property'=>'name'
            ))
            ->add('scope', 'entity', array(
                'class'=>'Lists\LookupBundle\Entity\Lookup',
                'property'=>'name',
                'mapped' => false,
                'query_builder' => $lr->getOnlyScopeQuery()
            ))
            ->add('users', 'entity', array(
                'class'=>'SD\UserBundle\Entity\User',
                'mapped' => false,
                'multiple' => true,
                'property'=>'fullname',
                'query_builder' => $ur->getOnlyStaff()
            ))
        ;

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
            'data_class' => 'Lists\HandlingBundle\Entity\Handling',
            'validation_groups' => false,
            'csrf_protection' => false
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'handlingSalesFilterForm';
    }
}
