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
 * StatisticCoworkerFilterFormType class
 *
 * Construct, generate form to filter coworkers
 */
class StatisticCoworkerFilterFormType extends AbstractType
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
            ->add('user', 'hidden', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'data-url'  => $router->generate('it_doors_oper_ajax_get_oper_manager'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_user_by_ids'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 0,
                        'allowClear' => true,
                        'width' => '200px',
                        'multiple' => 'multiple'
                    )),
                    'placeholder' => 'Enter opermanager'
                )
            ))
            ->add('companyStructure', 'text', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'data-url' => $router->generate('sd_common_ajax_company_structure'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_company_structure_by_id'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 0,
                        'allowClear' => true,
                        'width' => '250px',
                        'multiple' => 'multiple'
                    )),
                    'placeholder' => 'Enter company structure',
                )
            ));

/*        $builder
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
            ));*/

        $builder
            ->add('daterange', 'daterange', array(
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
        return 'StatisticCoworkerFilterFormType';
    }
}
