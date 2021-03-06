<?php

namespace Lists\HandlingBundle\Form;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Class HandlingSalesFormType
 */
class HandlingSalesFormType extends AbstractType
{
    protected $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Container $container */
        $container = $this->container;

        /** @var \Lists\LookupBundle\Entity\LookupRepository $lr */
//        $lr = $container->get('lists_lookup.repository');

        $builder
            ->add('organization', 'text', array(
                'disabled' => true
            ))
            ->add('user', 'text', array(
                'disabled' => true
            ))
            ->add('createdate', 'date', array(
                'data' => new \DateTime(),
                'widget' => 'single_text',
                'format' => 'dd.M.yyyy'
            ))
            ->add('status', 'entity', array(
                'class' => 'ListsHandlingBundle:HandlingStatus',
                'empty_value' => '',
                'query_builder' => function (\Lists\HandlingBundle\Entity\HandlingStatusRepository $repository) {
                        return $repository->createQueryBuilder('s')
                            ->orderBy('s.sortorder', 'ASC');
                }
            ))
            ->add('type', 'entity', array(
                'class' => 'ListsHandlingBundle:HandlingType',
                'empty_value' => '',
                'query_builder' => function (\Lists\HandlingBundle\Entity\HandlingTypeRepository $repository) {
                        return $repository->createQueryBuilder('s')
                            ->orderBy('s.sortorder', 'ASC');
                }
            ))
            ->add('statusDescription')
            ->add('handlingServices', 'entity', array(
                'class' => 'ListsHandlingBundle:HandlingService',
                'empty_value' => '',
                'multiple' => true,
                'query_builder' => function (\Lists\HandlingBundle\Entity\HandlingServiceRepository $repository) {
                        return $repository->createQueryBuilder('s')
                            ->orderBy('s.sortorder', 'ASC');
                }
            ))
            ->add('budget')
            ->add('square')
            ->add('chance')
            ->add('description')
            ->add('result', 'entity', array(
                'class' => 'ListsHandlingBundle:HandlingResult',
                'empty_value' => '',
                'required' => false,
                'query_builder' => function (\Lists\HandlingBundle\Entity\HandlingResultRepository $repository) {
                        return $repository->createQueryBuilder('s')
                            ->orderBy('s.sortorder', 'ASC');
                }
            ))
            ->add('resultString');

        $builder
            ->add('create', 'submit');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Lists\HandlingBundle\Entity\Handling',
            'validation_groups' => array('new'),
            'translation_domain' => 'ListsHandlingBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'handlingSalesForm';
    }
}
