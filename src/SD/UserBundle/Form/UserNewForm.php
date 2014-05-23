<?php

namespace SD\UserBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserNewForm extends AbstractType
{
    private $class;

    protected $container;

    /**
     * @param string    $class     The User class name
     * @param Container $container The User class name
     */
    public function __construct($class, $container)
    {
        $this->class = $class;
        $this->container = $container;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();

        $builder
            ->add('lastName', null, array(
                'constraints' => new NotBlank()
            ))
            ->add('firstName', null, array(
                'constraints' => new NotBlank()
            ))
            ->add('middleName')
            ->add('username')
            ->add('email')
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array('label' => 'form.new_password'),
                'second_options' => array('label' => 'form.new_password_confirmation'),
                'invalid_message' => 'fos_user.password.mismatch',
            ));

        // Staff
        $builder
            ->add('mobilephone', 'text', array(
                'mapped' => false,
                'constraints' => new NotBlank()
            ));

        $builder
            ->add('create', 'submit');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'validation_groups' => array('Registration')
        ));
    }

    public function getName()
    {
        return 'userNewForm';
    }
}
