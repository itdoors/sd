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
    private $claim;

    public function __construct($claim) {
        $this->claim = $claim;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('claim', 'hidden_entity', array(
                'entity' => 'SDServiceDeskBundle:Claim',
            ))
            ->add('claimPerformer', 'entity', array(
                'class' => 'SDBusinessRoleBundle:ClaimPerformer',
                'query_builder' => function(EntityRepository $er) use ($builder) {
                    $qb = $er->createQueryBuilder('p');
                    $qb2 = $er->createQueryBuilder('pi');

                    $qb2
                        ->join('SDServiceDeskBundle:ClaimPerformerRule', 'r', 'WITH', 'r.claimPerformer = pi.id')
                        ->where('r.claim = :claim');

                    return $qb
                        ->addSelect('i')
                        ->addSelect('u')
                        ->addSelect('s')
                        ->addSelect('st')
                        ->join('p.individual', 'i')
                        ->join('i.user', 'u')
                        ->join('u.stuff', 's')
                        ->join('s.status', 'st')
                        ->where('s.dateFire is NULL')
                        ->andWhere(
                            $qb->expr()->orX(
                                $qb->expr()->eq('st.lukey', ':worked'),
                                $qb->expr()->isNull('s.status')
                            )
                        )
                        ->andWhere(
                            $qb->expr()->notIn('p.id', $qb2->getDQL())
                        )
                        ->orderBy('u.lastName', 'ASC')
                        ->setParameter(':worked', 'worked')
                        ->setParameter(':claim', $this->claim->getId());
            }));
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
