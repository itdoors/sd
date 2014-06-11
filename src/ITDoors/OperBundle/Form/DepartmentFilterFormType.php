<?php

namespace ITDoors\OperBundle\Form;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Routing\Router;

/**
 * DepartmentFilterFormType class
 *
 * Construct, generate form to filter departments
 */
class DepartmentFilterFormType extends AbstractType
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
                    'data-url'  => $router->generate('sd_common_ajax_mpk'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_mpk_by_id'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 2,
                        'allowClear' => true,
                        'width' => '200px',
                        'multiple' => 'multiple'
                    )),
                    'placeholder' => 'Enter mpk',
                )
            ));

        $builder
            ->add('organization', 'hidden', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'data-url'  => $router->generate('sd_common_ajax_organization'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_organization_by_ids'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 2,
                        'allowClear' => true,
                        'width' => '200px',
                        'multiple' => 'multiple'
                    )),
                    'placeholder' => 'Enter organization',
                )
            ));

        $builder
            ->add('companyStructure', 'hidden', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'data-url'  => $router->generate('sd_common_ajax_company_structure'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_company_structure_by_id'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 0,
                        'allowClear' => true,
                        'width' => '200px',
                        'multiple' => 'multiple'
                    )),
                    'placeholder' => 'Enter company structure',
                )
            ));
        $builder
            ->add('region', 'hidden', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'data-url'  => $router->generate('sd_common_ajax_region'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_region_by_id'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 0,
                        'allowClear' => true,
                        'width' => '200px',
                        'multiple' => 'multiple'
                    )),
                    'placeholder' => 'Enter region',
                )
            ));

        $builder
            ->add('city', 'hidden', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'data-url'  => $router->generate('sd_common_ajax_city'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_city_by_id'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 2,
                        'allowClear' => true,
                        'width' => '200px',
                        'multiple' => 'multiple'
                    )),
                    'placeholder' => 'Enter city',
                )
            ));

        $builder
            ->add('status', 'hidden', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'data-url'  => $router->generate('sd_common_ajax_department_status'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_department_status_by_id'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 0,
                        'allowClear' => true,
                        'width' => '200px',
                        'multiple' => 'multiple'
                    )),
                    'placeholder' => 'Enter status',
                )
            ));

        $builder
            ->add('departmentType', 'hidden', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'data-url'  => $router->generate('sd_common_ajax_department_type'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_department_type_by_id'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 0,
                        'allowClear' => true,
                        'width' => '200px',
                        'multiple' => 'multiple'
                    )),
                    'placeholder' => 'Enter department type',
                )
            ));

        $builder
            ->add('address', 'text', array(
                'attr' => array(
                    'class' => 'form-control form-filter input-sm',
                    'placeholder' => 'Enter address',
                )
            ));

        $builder
            ->add('opermanager', 'hidden', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'data-url'  => $router->generate('sd_common_ajax_user'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_user_by_ids'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 1,
                        'allowClear' => true,
                        'width' => '200px',
                        'multiple' => 'multiple'
                    )),
                    'placeholder' => 'Enter opermanager',
                )
            ));

        $builder
            ->add('performer', 'hidden', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'data-url'  => $router->generate('sd_common_ajax_self_organization'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_organization_by_ids'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 2,
                        'allowClear' => true,
                        'width' => '200px',
                        'multiple' => 'multiple'
                    )),
                    'placeholder' => 'Enter performer',
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
        return 'DepartmentFilterFormType';
    }
}
