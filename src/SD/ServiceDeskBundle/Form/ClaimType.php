<?php

namespace SD\ServiceDeskBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use SD\UserBundle\SDUserBundle;

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
            ->add('type', 'choice', array('choices'   => \SD\ServiceDeskBundle\Entity\ClaimType::values()))
            ->add('status', 'choice', array('choices'   => \SD\ServiceDeskBundle\Entity\StatusType::values()))
            ->add('importance', 'choice', array('choices'   => \SD\ServiceDeskBundle\Entity\ImportanceType::values()))
            ->add('createdAt', 'date', array(
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy'
            ))
            ->add('closedAt', 'date', array(
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy',
                'required' => false
            ))
            ->add('disabled', 'checkbox', array('required' => false))
            ->add('customer')
//             ->add('performers', 'entity', array(
//                 'class' => 'SDBusinessRoleBundle:Staff',
//                 'required' => false,
//                 'multiple' => true
//             ))
            ->add('text');
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
