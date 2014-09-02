<?php

namespace ITDoors\HistoryBundle\Form;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class HistoryFilterFormType
 */
class HistoryFilterFormType extends AbstractType
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
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('action', 'itdoors_choice', array(
                    'empty_value' => '',
                    'attr' => array(
                        'class' => 'itdoors-select2 can-be-reseted',
                        'class_outer' => 'col-md-4',
                        'data-params' => json_encode(array(
                            'minimumInputLength' => 0,
                            'width' => '200px'
                        )),
                        'placeholder' => $this->container->get('translator')
                            ->trans('Select action', array(), 'ITDoorsHistoryBundle')
                    ),
                    'choices' => array(
                        'insert' => $this->container->get('translator')
                            ->trans('Insert', array(), 'ITDoorsHistoryBundle'),
                        'update' => $this->container->get('translator')
                            ->trans('Change', array(), 'ITDoorsHistoryBundle'),
                        'delete' => $this->container->get('translator')
                            ->trans('Delete', array(), 'ITDoorsHistoryBundle')
                    )
                ));

        $builder
            ->add('submit', 'submit')
            ->add('reset', 'submit');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => false,
            'csrf_protection' => false,
            'translation_domain' => 'ITDoorsHistoryBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'historyFilterForm';
    }
}
