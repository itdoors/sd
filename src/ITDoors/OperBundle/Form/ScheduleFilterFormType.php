<?php
/**
 * Created by PhpStorm.
 * User: silence
 * Date: 19.05.14
 * Time: 10:59
 */

namespace ITDoors\OperBundle\Form;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Routing\Router;

/**
 * CoworkerFilterFormType class
 *
 * Construct, generate form to filter coworkers
 */
class ScheduleFilterFormType extends AbstractType
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
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Router $router */
        $router = $this->container->get('router');

        $builder
            ->add('mpk', 'hidden', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'data-url'  => $router->generate('sd_common_ajax_oper_department_mpk'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_mpk_by_id'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 2,
                        'allowClear' => true,
                        'width' => '200px',
                        'multiple' => 'multiple'
                    )),
                    'data-id-department' => 3,
                    'placeholder' => 'Enter mpk',
                )
            ));

        $builder
            ->add('coworker', 'hidden', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'data-url'  => $router->generate('sd_common_ajax_oper_department_individual'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_oper_department_individual_by_id'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 2,
                        'allowClear' => true,
                        'width' => '200px',
                        'multiple' => 'multiple'
                    )),
                    'placeholder' => 'Enter indvidual',
                )
            ));

        $builder
            ->add('year', 'hidden', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'data-url'  => $router->generate('sd_common_ajax_oper_schedule_year'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_oper_schedule_year_by_id'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 0,
                        'allowClear' => true,
                        'width' => '200px',
                    )),
                    'placeholder' => 'Enter year',
                ),
            ));

        $builder
            ->add('month', 'hidden', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'data-url'  => $router->generate('sd_common_ajax_oper_schedule_month'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_oper_schedule_month_by_id'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 0,
                        'allowClear' => true,
                        'width' => '200px',
                    )),
                    'placeholder' => 'Enter month',
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
            'translation_domain' => 'ITDoorsOperBundle'
        ));
    }

    /**
     * getName
     *
     * @return string
     */
    public function getName()
    {
        return 'ScheduleFilterFormType';
    }
}
