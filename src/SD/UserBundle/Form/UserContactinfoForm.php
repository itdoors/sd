<?php

namespace SD\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use SD\UserBundle\Entity\ContactinfoRepository;
use Symfony\Component\DependencyInjection\Container;

/**
 * UserContactinfoForm
 */
class UserContactinfoForm extends AbstractType
{
    /**
     * @var \ProjectServiceContainer $container
     */
    protected $container;

    /**
     * __construct
     * 
     * @param Container $container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     * 
     * @return FormBuilderInterface
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contactinfo', 'entity', array(
                'class' => 'SDUserBundle:Contactinfo',
                'empty_value' => '',
                'required' => false,
                'mapped' => false,
                'query_builder' => function (ContactinfoRepository $repository) {
                    return $repository->createQueryBuilder('mc');
                }
            ))
            ->add('value', 'text', array(
                'attr' => array(
                    'class' => 'itdoors-select2 can-be-reseted submit-field',
                    'placeholder' => 'Enter value',
                )
            ))
            ->add('add', 'submit')
            ->add('cancel', 'submit');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => false,
            'csrf_protection' => false,
            'translation_domain' => 'SDUserBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'userContactinfoForm';
    }
}
