<?php

namespace Lists\IndividualBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IndividualType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', null, array('required' => false))
            ->add('middleName', null, array('required' => false))
            ->add('lastName', null, array('required' => false))
            ->add('birthday', 'date', array(
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy',
                'required' => false
            ))
            ->add('tin', null, array('required' => false))
            ->add('passport', null, array('required' => false))
            ->add('address', null, array('required' => false))
            ->add('phone', null, array('required' => false));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Lists\IndividualBundle\Entity\Individual'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'lists_individualbundle_individual';
    }
}
