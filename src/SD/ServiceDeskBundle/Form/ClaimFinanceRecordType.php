<?php

namespace SD\ServiceDeskBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * ClaimFinanceRecordType
 */
class ClaimFinanceRecordType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mpk')
            ->add('work')
            ->add('incomeNDS')
            ->add('costsNonNDS')
            ->add('costsNDS')
            ->add('claim', 'hidden_entity', array(
                'entity' => 'SDServiceDeskBundle:Claim',
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SD\ServiceDeskBundle\Entity\ClaimFinanceRecord'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'claimFinanceForm';
    }
}
