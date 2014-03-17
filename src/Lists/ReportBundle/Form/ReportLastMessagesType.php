<?php

namespace Lists\ReportBundle\Form;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Routing\Router;

/**
 * Report last messages filter form
 */
class ReportLastMessagesType extends AbstractType
{
    /**
     * @var Container $container
     */
    protected $container;

    /**
     * __construct
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Router $router */
        $router = $this->container->get('router');

        $builder
            ->add('userId', 'hidden', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'data-url'  => $router->generate('sd_common_ajax_user'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_user_by_id'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 2,
                        'allowClear' => true,
                        'width' => '200px',
                    )),
                    'placeholder' => 'Enter Manager'
                )
            ));

        $builder
            ->add('daterangecustom', 'daterangecustom');

        $builder
            ->add('withDaterange', 'checkbox', array(
                'label' => 'Interval',
                'attr' => array(
                    'class_outer' => 'col-md-2',
                    'class' => 'can-be-reseted'
                )
            ));

        $builder
            ->add('daterange', 'daterange', array(
                'attr' => array(
                    'class' => 'hidden'
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
            'translation_domain' => 'ListsHandlingBundle'
        ));
    }

    /**
     * @{@inheritDoc}
     */
    public function getName()
    {
        return 'reportLastMessagesType';
    }
}