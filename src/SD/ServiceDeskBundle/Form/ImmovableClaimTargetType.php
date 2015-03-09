<?php

namespace SD\ServiceDeskBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * ImmovableClaimTargetType
 */
class ImmovableClaimTargetType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', 'choice', array('choices'   => \SD\ServiceDeskBundle\Entity\ImmovableClaimTargetType::values()))
            ->add('street', 'text')
            ->add('bld', 'text')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SD\ServiceDeskBundle\Entity\ImmovableClaimTarget'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'immovableClaimTargetForm';
    }
}
