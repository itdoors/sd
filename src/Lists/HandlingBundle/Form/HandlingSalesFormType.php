<?php

namespace Lists\HandlingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class HandlingSalesFormType extends AbstractType
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
            ->add('createdate')
            ->add('status')
            ->add('type')
            ->add('statusDescription')
            ->add('serviceOffered')
            ->add('budget')
            ->add('square')
            ->add('chance')
            ->add('description')
            ->add('result')
            ->add('resultString')

        ;

        $builder
            ->add('create', 'submit');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Lists\HandlingBundle\Entity\Handling',
            'validation_groups' => array('new')
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'handlingSalesForm';
    }
}
