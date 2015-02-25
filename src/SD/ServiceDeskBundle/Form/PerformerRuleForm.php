<?php

namespace SD\ServiceDeskBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Doctrine\ORM\EntityRepository;

/**
 * Class PerformerRuleForm
 */
class PerformerRuleForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('claimPerformer', 'entity', array(
                'class' => 'SDBusinessRoleBundle:ClaimPerformer',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->addSelect('i')
                        ->addSelect('u')
                        ->addSelect('s')
                        ->join('p.individual', 'i')
                        ->join('i.user', 'u')
                        ->join('u.stuff', 's');
            }))
            ->add('claim', 'hidden_entity', array(
                'entity' => 'SDServiceDeskBundle:Claim',
            ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SD\ServiceDeskBundle\Entity\ClaimPerformerRule'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'performerRuleForm';
    }
}
