<?php

namespace SD\ServiceDeskBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * ClaimType
 */
class ClaimType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('types', 'text', array())
            ->add('status', 'text', array())
            ->add('importance', 'text', array())
            ->add('createdAt', 'date', array(
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy'
            ))
            ->add('closedAt', 'text', array())
            ->add('disabled', 'text', array())
            ->add('customer', 'text', array())
            ->add('curators', 'text', array())
            ->add('performers', 'text', array());
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SD\ServiceDeskBundle\Entity\Claim'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sd_servicedeskbundle_claim';
    }
}
