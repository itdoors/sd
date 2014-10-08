<?php

namespace Lists\DogovorBundle\Form;

use Lists\DogovorBundle\Entity\DogovorRepository;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class DogovorFilterForm
 */
class DogovorMadeFilterForm extends AbstractType
{
    /**
     * @var \ProjectServiceContainer $container
     */
    protected $container;

    /**
     * __construct()
     *
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
        $router = $this->container->get('router');

        $builder
            ->add('dogovorType', 'text', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'data-url' => $router->generate('sd_common_ajax_dogovor_type'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_scope_by_ids'),
                    'empty_value' =>  '',
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 0,
                        'allowClear' => true,
                        'width' => '250px',
                        'multiple' => true
                    )),
                    'placeholder' => 'Enter Dogovor Type'
                )
            ))
            ->add('dateRange', 'text', array(
                'attr' => array(
                    'empty_value' =>  '',
                    'class' => 'daterangepicker input-daterange',
                    'placeholder' => 'Enter date range'
                )
            ));

    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => false,
            'csrf_protection' => false,
            'translation_domain' => 'ListsDogovorBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'dogovorMadeFilterForm';
    }
}
