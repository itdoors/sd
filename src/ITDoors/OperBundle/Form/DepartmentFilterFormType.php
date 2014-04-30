<?php

namespace ITDoors\OperBundle\Form;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
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
                    'placeholder' => 'Enter Manager',
                )
            ));


        /*$builder
            ->add('organization', 'entity', array(
                    'class'=>'Lists\DepartmentBundle\Entity\Departments',
                    'mapped' => false,
                    'property'=>'organization',
                    'attr' => array(
                        'class' => 'itdoors-select2 can-be-reseted submit-field',
                        'placeholder' => 'Enter organization',
                        'multiple' => 'multiple'
                    )
                )

            );
        $builder
            ->add('city', 'entity', array(
                'class'=>'Lists\CityBundle\Entity\City',
                'mapped' => false,
                'property'=>'name',
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'placeholder' => 'Enter city',
                    'multiple' => 'multiple'
                )
            )
        );*/


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
     * getName
     *
     * @return string
     */
    public function getName()
    {
        return 'DepartmentFilterFormType';
    }
}
