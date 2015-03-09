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
            ->add('address', 'text')
            ->add('bld', 'text', array('mapped' => false))
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
