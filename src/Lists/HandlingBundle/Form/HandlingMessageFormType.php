<?php

namespace Lists\HandlingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class HandlingMessageFormType extends AbstractType
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
            ->add('createdate', 'datetime', array(
                'data' => new \DateTime()
            ))
            ->add('type', null, array(
                'empty_value' => '',
                'required' => true,
            ))
            ->add('nextcreatedate', 'datetime', array(
                'required' => true,
                'mapped' => false,
                'empty_value' => ''
            ))
            ->add('nexttype', 'entity', array(
                'class' => 'ListsHandlingBundle:HandlingMessageType',
                'required' => true,
                'empty_value' => '',
                'mapped' => false
            ))
            ->add('description')
            ->add('filename')
            ->add('file', 'file', array(
                'required' => false
            ))
            ->add('handling_id', 'hidden')

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
            'data_class' => 'Lists\HandlingBundle\Entity\HandlingMessage',
            'validation_groups' => array('new')
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'handlingMessageForm';
    }
}
