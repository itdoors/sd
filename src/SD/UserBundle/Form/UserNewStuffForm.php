<?php

namespace SD\UserBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Lists\CompanystructureBundle\Entity\CompanystructureRepository;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * UserNewForm
 */
class UserNewStuffForm extends AbstractType
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
        $user = $this->container->get('security.context')->getToken()->getUser();

        $builder
            ->add('lastName', null, array(
                'constraints' => new NotBlank()
            ))
            ->add('firstName', null, array(
                'constraints' => new NotBlank()
            ))
            ->add('middleName')
            ->add('birthday', 'text')
            ->add('username')
            ->add('email')
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'options' => array('translation_domain' => 'SDUserBundle'),
                'first_options' => array('label' => 'form.new_password'),
                'second_options' => array('label' => 'form.new_password_confirmation'),
                'invalid_message' => 'fos_user.password.mismatch',
        ));

        // Stuff
        $builder
            ->add('mobilephone', 'text', array(
                'mapped' => false,
                'constraints' => new NotBlank()
            ))
            ->add('hiredate', 'text', array(
                'mapped' => false,
                'constraints' => new NotBlank()
            ))
            ->add('position', 'text', array(
                'mapped' => false
            ))
            ->add('education', 'textarea', array(
                'constraints' => new NotBlank(),
                'mapped' => false,
                'required' => false
            ))
            ->add('issues', 'textarea', array(
                'mapped' => false,
                'constraints' => new NotBlank()
            ))
            ->add('companystructure', 'entity', array(
                'mapped' => false,
                'class' => 'ListsCompanystructureBundle:Companystructure',
                'empty_value' => '',
                'required' => true,
                'query_builder' => function (CompanystructureRepository $repository) {
                    return  $repository->createQueryBuilder('cs');
                }
            ));

        $builder
            ->add('create', 'submit');
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SD\UserBundle\Entity\User',
            'validation_groups' => array('Registration'),
            'translation_domain' => 'SDUserBundle'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'userNewStuffForm';
    }
}
