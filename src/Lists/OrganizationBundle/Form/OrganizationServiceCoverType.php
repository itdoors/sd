<?php

namespace Lists\OrganizationBundle\Form;

use ITDoors\CommonBundle\Services\BaseService;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class OrganizationServiceCoverType
 */
class OrganizationServiceCoverType extends AbstractType
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

        /** @var BaseService $baseService */
        $baseService = $container->get('itdoors_common.base.service');

        $builder
            ->add('serviceId', 'itdoors_select2', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'class_outer' => 'col-md-4',
                    'data-url'  => $router->generate('sd_common_ajax_handling_service'),
                    //'data-url-by-id' => $router->generate('sd_common_ajax_organization_by_id'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 0,
                        'allowClear' => true,
                        'width' => '300px',
                        )),
                    'placeholder' => 'Enter Service'
                    ),
                'required'  => true,
                'error_bubbling' => false,
            ))
            ->add('isInterested', 'itdoors_choice', array(
                'choices' => $baseService->getYesNoChoices(),
                'empty_value' => 'Enter Is interested',
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted',
                    'class_outer' => 'col-md-4',
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 0,
                        'width' => '200px'
                    )),
                    'placeholder' => 'Enter Is interested'
                ),

            ))
            ->add('isWorking', 'itdoors_choice', array(
                'choices' => $baseService->getYesNoChoices(),
                'empty_value' => 'Enter Is working',
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted',
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 0,
                        'width' => '200px'
                    )),
                    'placeholder' => 'Enter Is working'
                ),

            ))
            ->add('evaluation', 'itdoors_choice', array(
                'choices' => $baseService->getNumberChoices(1, 10),
                'empty_value' => 'Evaluation',
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted',
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 0,
                        'width' => '200px'
                    )),
                    'placeholder' => 'Evaluation'
                ),

            ))
            ->add('competitorId', 'itdoors_select2', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'class_outer' => 'col-md-4',
                    'data-url'  => $router->generate('sd_common_ajax_competitor'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_organization_by_id'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 2,
                        'allowClear' => true,
                        'width' => '300px',
                    )),
                    'placeholder' => 'Enter Competitor'
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
            ))
            ->add('responsible', null, array(
                'attr' => array(
                    'class' => 'form-control',
                    'class_outer' => 'col-md-3',
                    'placeholder' => 'Enter Responsible'
                )
            ))
            ->add('description', null, array(
                'attr' => array(
                    'class' => 'form-control',
                    'class_outer' => 'col-md-3',
                    'placeholder' => 'Enter Description'
                )
            ));

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($container) {
                $data = $event->getData();

                $form = $event->getForm();

                if ($data) {
                    if (!$data['serviceId']) {
                        $translator = $container->get('translator');

                        $msg = $translator->trans("Service cant be empty", array(), 'ListsOrganizationBundle');

                        $form->addError(new FormError($msg));
                    }
                }
            }
        );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('new'),
            'translation_domain' => 'ListsOrganizationBundle',
            'error_bubbling' => false
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'organizationServiceCoverType';
    }
}
