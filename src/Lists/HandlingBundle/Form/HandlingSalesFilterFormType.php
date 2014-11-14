<?php

namespace Lists\HandlingBundle\Form;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class HandlingSalesFilterFormType
 */
class HandlingSalesFilterFormType extends AbstractType
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
        /** @var \Lists\LookupBundle\Entity\LookupRepository $lr */
        $lr = $this->container->get('lists_lookup.repository');

        /** @var \SD\UserBundle\Entity\UserRepository $ur */
        $ur = $this->container->get('sd_user.repository');

        $builder
            ->add('organization', 'text', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'data-url' => $router->generate('sd_common_ajax_organization_for_contacts'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_organization_by_ids'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 2,
                        'allowClear' => true,
                        'width' => '100%',
                        'multiple' => 'multiple'
                    )),
                    'placeholder' => 'Enter Organiztion',
                )
            ))
            ->add('city', 'text', array(
                'attr' => array(
                    'class' => 'form-control itdoors-select2',
                    'data-url' => $router->generate('sd_common_ajax_city'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_city_by_id'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 2,
                        'allowClear' => true,
                        'width' => '100%',
                        'multiple' => 'multiple'
                    )),
                    'placeholder' => 'Enter City'
                )
            ))
            ->add('scope', 'text', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'data-url' => $router->generate('sd_common_ajax_scope'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_scope_by_id'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 0,
                        'allowClear' => true,
                        'width' => '100%',
                        'multiple' => 'multiple'
                    )),
                    'placeholder' => 'Enter Scope',
                )
            ))
            ->add('users', 'text', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'data-url' => $router->generate('sd_common_ajax_user_fio'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_user_by_ids'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 2,
                        'allowClear' => true,
                        'width' => '100%',
                        'multiple' => 'multiple'
                    )),
                    'placeholder' => 'Enter Manager',
                )
            ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null,
            'validation_groups' => false,
            'csrf_protection' => false,
            'translation_domain' => 'ListsHandlingBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'handlingSalesFilterForm';
    }
}
