<?php

namespace Lists\CoachBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * ReportFilterFormType
 */
class ReportFilterFormType extends AbstractType
{
    /**
     * @var \ProjectServiceContainer $container
     */
    protected $container;

    /**
     * __construct
     * 
     * @param type $container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $translator = $this->container->get('translator');
        $router = $this->container->get('router');

        $builder
            ->add('author', 'text', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'data-url' => $router->generate('lists_coach_ajax_coach_list'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_user_by_ids'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 0,
                        'allowClear' => true,
                        'width' => '200px',
                        'multiple' => 'multiple'
                    )),
                    'placeholder' => 'Autor',
                )
            ))
            ->add('type', 'text', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'data-url' => $router->generate('lists_coach_ajax_action_types'),
                    'data-url-by-id' => $router->generate('lists_coach_ajax_action_types_by_ids'),
                    'data-params' => json_encode(array(
                                    'minimumInputLength' => 0,
                                    'allowClear' => true,
                                    'width' => '200px',
                                    'multiple' => 'multiple'
                    )),
                    'placeholder' => 'Action type',
                )
            ))
            ->add('topic', 'text', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'data-url' => $router->generate('lists_coach_ajax_action_topics'),
                    'data-url-by-id' => $router->generate('lists_coach_ajax_action_topics_by_ids'),
                    'data-params' => json_encode(array(
                                    'minimumInputLength' => 0,
                                    'allowClear' => true,
                                    'width' => '200px',
                                    'multiple' => 'multiple'
                    )),
                    'placeholder' => 'Action topic',
                )
            ))
            ->add('organization', 'text', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'data-url' => $router->generate('lists_coach_ajax_organization_list'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_organization_by_ids'),
                    'data-params' => json_encode(array(
                                    'minimumInputLength' => 0,
                                    'allowClear' => true,
                                    'width' => '200px',
                                    'multiple' => 'multiple'
                    )),
//                     'disabled' => true,
                    'placeholder' => 'Organization',
                )
            ))
            ->add('city', 'text', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'data-url' => $router->generate('lists_coach_ajax_city_list'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_city_by_id'),
                    'data-params' => json_encode(array(
                                    'minimumInputLength' => 0,
                                    'allowClear' => true,
                                    'width' => '200px',
                                    'multiple' => 'multiple'
                    )),
//                     'disabled' => true,
                    'placeholder' => 'City',
                )
            ))
            ->add('department', 'text', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'data-url' => $router->generate('sd_common_ajax_contact_phone'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_user_by_ids'),
                    'data-params' => json_encode(array(
                                    'minimumInputLength' => 2,
                                    'allowClear' => true,
                                    'width' => '200px',
                                    'multiple' => 'multiple'
                    )),
                    'disabled' => true,
                    'placeholder' => 'Place',
                )
            ))
            ->add('members', 'text', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'data-url' => $router->generate('sd_common_ajax_contact_phone'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_user_by_ids'),
                    'data-params' => json_encode(array(
                                    'minimumInputLength' => 2,
                                    'allowClear' => true,
                                    'width' => '200px',
                                    'multiple' => 'multiple'
                    )),
                    'disabled' => true,
                    'placeholder' => 'Members',
                )
            ))
            ->add('startedAt', 'date', array(
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy',
                'attr' => array(
                    'class' => 'form-control can-be-reseted itdoors-date-picker',
                    'data-params' => json_encode(array(
                                    'allowClear' => true,
                                    'width' => '200px',
                    )),
//                     'disabled' => true,
                    'placeholder' => 'Action date',
                )
            ))
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
            'translation_domain' => 'ListsCoachBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'reportFilterForm';
    }
}
