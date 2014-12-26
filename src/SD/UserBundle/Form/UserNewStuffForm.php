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
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

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
//        $user = $this->container->get('security.context')->getToken()->getUser();

        $container = $this->container;

        $repository = $this->container->get('doctrine')->getRepository('ListsCompanystructureBundle:Companystructure');

        $builder
            ->add('lastName', null, array(
                'constraints' => new NotBlank()
            ))
            ->add('firstName', null, array(
                'constraints' => new NotBlank()
            ))
            ->add('middleName')
//            ->add('birthday', 'text', array(
//                'required' => false,
//            ))
            ->add('username')
            ->add('email')
            ->add('userPosition', 'entity', array(
                'mapped' => false,
                'class' => 'SDUserBundle:Position',
                'empty_value' => '',
                'required' => true,
                'property' => 'name'
            ));

        // Stuff
        $builder
//            ->add('mobilephone', 'text', array(
//                'mapped' => false,
//                'required' => false
//            ))
//            ->add('hiredate', 'text', array(
//                'mapped' => false,
//                'constraints' => new NotBlank()
//            ))
//            ->add('position', 'text')
//            ->add('education', 'textarea', array(
//                'constraints' => new NotBlank(),
//                'mapped' => false,
//                'required' => false
//            ))
//            ->add('issues', 'textarea', array(
//                'mapped' => false,
//                'required' => false,
//                'constraints' => new NotBlank()
//            ))
            ->add('companystructure', 'entity', array(
                'mapped' => false,
                'class' => 'ListsCompanystructureBundle:Companystructure',
                'empty_value' => '',
                'required' => true,
                'property' => 'name_for_list',
                'query_builder' => function ($repository) use ($repository) {

                return $repository->createQueryBuilder('c')
                    ->orderBy('c.root, c.lft', 'ASC');
                }
            ));

        $builder
            ->add('create', 'submit');

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($container) {
                /** @var User $newUser */
                $newUser = $event->getData();
                $form = $event->getForm();
                $em = $container->get('doctrine')->getManager();
                $existingUser = $em->getRepository('SDUserBundle:User')
                    ->findOneBy(array('email' => $newUser->getEmail()));
                if ($existingUser) {
                    $form->get('email')->addError(new FormError($existingUser));
                }
                $existingUser = $em->getRepository('SDUserBundle:User')
                    ->findOneBy(array('username' => $newUser->getUserName()));
                if ($existingUser) {
                        $form->get('username')->addError(new FormError($existingUser));
                }
            }
        );
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
