<?php

namespace Lists\ReportBundle\Form;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class ReportActivitiFilterType
 */
class ReportActivityFilterType extends AbstractType
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
        $container = $this->container;

        $router = $container->get('router');

        $builder
            ->add('userId', 'itdoors_select2', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'class_outer' => 'col-md-4',
                    'data-url'  => $router->generate('sd_common_ajax_user'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_user_by_id'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 2,
                        'allowClear' => true,
                        'width' => '300px',
                    )),
                    'placeholder' => 'Enter Manager'
                )
            ))
            ->add('endDate', 'itdoors_date_decade', array(
                'widget' => 'single_text',
                'format' => 'dd.M.yyyy',
                'attr' => array(
                    'class' => 'form-control',
                    'class_outer' => 'col-md-3',
                    'placeholder' => 'Enter End Date'
                )
            ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('new'),
            'translation_domain' => 'ListsHandlingBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'reportActivityFilterType';
    }
}
