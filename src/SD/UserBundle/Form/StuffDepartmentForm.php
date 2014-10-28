<?php

namespace SD\UserBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\DependencyInjection\Container;

/**
 * StuffDepartmentForm
 */
class StuffDepartmentForm extends AbstractType
{
    protected $container;

    /**
     * __construct
     *
     * @param Container $container The User class name
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $router = $this->container->get('router');
        
        $em = $this->container->get('doctrine')->getManager();
        $claimtypes = $em->getRepository('SDUserBundle:Claimtype')->createQueryBuilder('c');

        $builder
            ->add('stuff', 'text', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'data-url' => $router->generate('sd_common_ajax_user_fio'),
                    'data-url-by-id' => $router->generate('sd_common_ajax_user_by_ids'),
                    'data-params' => json_encode(array(
                        'minimumInputLength' => 2,
                        'width' => '200px'
                    )),
                    'placeholder' => 'Enter fio',
                ),
                'mapped' => false
            ))
            ->add('userkey', 'choice', array(
                'choices' => array(
                    'kurator' => 'kurator',
                    'stuff' => 'stuff'
                )
            ))
            ->add('claimtype', 'entity', array(
                'class' => 'SDUserBundle:Claimtype',
                'query_builder' => $claimtypes,
                'multiple' => 'multiple',
                'mapped' => false
            ))
            
            ->add('departments', 'hidden', array(
            ));

         $builder
            ->add('submit', 'submit')
            ->add('cancel', 'submit');
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null,
            'translation_domain' => 'SDUserBundle'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'stuffDepartmentForm';
    }
}
