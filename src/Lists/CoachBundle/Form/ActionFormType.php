<?php
namespace Lists\CoachBundle\Form;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class ActionFormType
 */
class ActionFormType extends AbstractType
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
        $router = $this->container->get('router');
        $builder
            ->add('department', 'itdoors_select2', array(
                'mapped' => false,
                'attr' => array(
                    'class' => 'form-control itdoors-select2 can-be-reseted submit-field',
                    'data-url' => $router->generate('sd_common_ajax_departments_by_city_id'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_department_by_id'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 0,
                        'allowClear' => true
                    ))
                )
        ))
            ->add('individuals', 'text', array(
                'mapped' => false,
                'attr' => array(
                    'class' => 'form-control itdoors-select2 can-be-reseted submit-field',
                    'data-url' => $router->generate('sd_common_ajax_individuals_by_city_id'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_oper_department_individual'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 0,
                        'allowClear' => true,
                        'multiple' => 'multiple'
                    )),
                )
        ))
            ->add('startedAt', 'date', array(
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy'
        ))
            ->add('type', 'entity', array(
                'class' => 'Lists\CoachBundle\Entity\ActionType',
                'property' => 'title',
                'empty_value' => '',
                'multiple' => false,
                'attr' => array(
                     'data-params' => json_encode(array(
                        'minimumInputLength' => 0,
                        'allowClear' => true
                    )))
        ))
            ->add('topic', 'entity', array(
                'class' => 'Lists\CoachBundle\Entity\ActionTopic',
                'property' => 'title',
                'empty_value' => '',
                'multiple' => false,
                'required' => false,
                'attr' => array(
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 0,
                        'allowClear' => true
                    )))
        ));
//             ->add('text', 'textarea', array(
//                 'required' => false
//             ));
    }

    /**
     * @param OptionsResolverInterface $resolver            
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Lists\CoachBundle\Entity\Action',
            'validation_groups' => array(
                'new'
            ),
            'translation_domain' => 'ListsCoachBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'actionForm';
    }
}
