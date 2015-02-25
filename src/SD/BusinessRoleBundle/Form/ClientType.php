<?php

namespace SD\BusinessRoleBundle\Form;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * ClientType
 */
class ClientType extends AbstractType
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
            ->add('individual', 'hidden_entity', array(
                'entity' => 'ListsIndividualBundle:Individual',
                'required' => false,
                'attr' => array(
                    'class' => 'form-control itdoors-select2 can-be-reseted submit-field',
                    'data-url' => $router->generate('sd_common_ajax_departments_by_city_id'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_department_by_id'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 2,
                        'allowClear' => true
                    ))
                )
            ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SD\BusinessRoleBundle\Entity\Client'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'clientAddForm';
    }
}
