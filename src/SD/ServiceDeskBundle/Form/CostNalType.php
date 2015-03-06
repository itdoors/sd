<?php

namespace SD\ServiceDeskBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * CostNalType
 */
class CostNalType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', 'choice', array('choices'   => \SD\ServiceDeskBundle\Entity\CostNalType::values()))
            ->add('value')
            ->add('financeRecord', 'hidden_entity', array(
                'entity' => 'SDServiceDeskBundle:ClaimFinanceRecord',
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SD\ServiceDeskBundle\Entity\CostNal'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'costNalForm';
    }
}
