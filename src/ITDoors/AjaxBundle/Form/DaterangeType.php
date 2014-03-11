<?php

namespace ITDoors\AjaxBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * DaterangeType
 */
class DaterangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', 'text', array(
                'attr' => array(
                    'class_outer' => 'input-group  sd-daterange',
                    'class' => 'form-control can-be-reseted sd-daterange-text',
                    'placeholder' => 'Enter date range'
                )
            ))
            ->add('start', 'date', array(
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy',
                'attr' => array(
                    'class_outer' => 'hidden',
                    'class' => 'sd-daterange-start can-be-reseted'
                )
            ))
            ->add('end', 'date', array(
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy',
                'attr' => array(
                    'class_outer' => 'hidden',
                    'class' => 'sd-daterange-end can-be-reseted'
                )
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'invalid_message' => 'Invalid object',
            'entity' => null,
            'error_bubbling' => false,
            'compound' => true
        ));
    }

    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'daterange';
    }
}