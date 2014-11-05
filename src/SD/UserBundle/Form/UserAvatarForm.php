<?php

namespace SD\UserBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\DependencyInjection\Container;

/**
 * UserAvatarForm
 */
class UserAvatarForm extends AbstractType
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
        $builder
            ->add('photo', 'file', array(
                'mapped' => false,
                'required' => false
            ))
            ->add('loadPhoto', 'hidden', array(
                'mapped' => false,
            ))
            ->add('x', 'hidden', array(
                'mapped' => false,
            ))
            ->add('y', 'hidden', array(
                'mapped' => false,
            ))
            ->add('w', 'hidden', array(
                'mapped' => false,
            ))
            ->add('h', 'hidden', array(
                'mapped' => false,
            ));

         $builder
            ->add('save', 'submit')
            ->add('cancel', 'submit');
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'userAvatarForm';
    }
}
