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
                    'data-url' => $router->generate('sd_common_ajax_user_fio'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_user_by_ids'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 0,
                        'allowClear' => true,
                        'width' => '200px',
                        'multiple' => 'multiple'
                    )),
                    'disabled' => true,
                    'placeholder' => $translator->trans("Autor", array(), 'ListsCoachBundle.ru'),
                )
            ))
            ->add('type', 'text', array(
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
                    'placeholder' => $translator->trans("Action type", array(), 'ListsCoachBundle.ru'),
                )
            ))
            ->add('topic', 'text', array(
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
                    'placeholder' => $translator->trans("Action topic", array(), 'ListsCoachBundle.ru'),
                )
            ))
            ->add('city', 'text', array(
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
                    'placeholder' => $translator->trans("City", array(), 'ListsCoachBundle.ru'),
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
                    'placeholder' => $translator->trans("Place", array(), 'ListsCoachBundle.ru'),
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
                    'placeholder' => $translator->trans("Members", array(), 'ListsCoachBundle.ru'),
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
