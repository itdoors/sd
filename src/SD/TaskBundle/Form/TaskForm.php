<?php

namespace SD\TaskBundle\Form;

use SD\UserBundle\SDUserBundle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * TaskForm class
 */
class TaskForm extends AbstractType
{
    /**
     * @var \ProjectServiceContainer $container
     */
    protected $container;

    /**
     * __construct()
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
     * 
     * @return mixed
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $container = $this->container;

        $user = $this->container->get('security.context')->getToken()->getUser();
        $userId = $user->getId();
        /** @var \SD\UserBundle\Entity\Stuff $stuff */
        $stuff = $container->get("doctrine")->getRepository('SDUserBundle:Stuff')
            ->findBy(array(
                'user' => $user
            ));

        if (!$stuff) {
            $builder->add('performer', 'entity', array(
                'mapped' => false,
                'class' => 'SD\UserBundle\Entity\User',
                'empty_value' => '',
                //'data' => $user,
                'query_builder' => function (\SD\UserBundle\Entity\UserRepository $repository) use ($userId) {
                        return $repository->createQueryBuilder('u')
                            ->where('u.id = :user')
                            ->setParameter(':user', $userId);
                }
            ));
            $builder->add('controller', 'entity', array(
                'mapped' => false,
                'class' => 'SD\UserBundle\Entity\User',
                'empty_value' => '',
                'data' => $user,
                'query_builder' => function (\SD\UserBundle\Entity\UserRepository $repository) use ($userId) {
                        return $repository->createQueryBuilder('u')
                            ->where('u.id = :user')
                            ->setParameter(':user', $userId);
                }
            ));

            $builder->add('matcher', 'entity', array(
                'mapped' => false,
                'class' => 'SD\UserBundle\Entity\User',
                'empty_value' => '',
                'required' => false,
                'multiple' => true,
                //'data' => $user,
                'query_builder' => function (\SD\UserBundle\Entity\UserRepository $repository) use ($userId) {
                        return $repository->createQueryBuilder('u')
                            ->where('u.id = :user')
                            ->setParameter(':user', $userId);
                }
            ));

        } else {
            //$companyStructure = $stuff->getCompanystructure();
            $builder->add('performer', 'entity', array(
                'mapped' => false,
                'class' => 'SD\UserBundle\Entity\User',
                'empty_value' => '',
                //'multiple' => true,
                'query_builder' => function (\SD\UserBundle\Entity\UserRepository $repository) {
                        return $repository->createQueryBuilder('u')
                            ->innerJoin('u.stuff', 's')
                            ->leftJoin('s.status', 'st')
                            ->where('st.lukey = :status')
                            ->orWhere('st.id is NULL')
                            ->orderBy('u.lastName', 'asc')
                            ->setParameter(':status', "worked");
                            //->setParameter(':fired', true, \PDO::PARAM_BOOL);
                }
            ));
            $builder->add('controller', 'entity', array(
                'mapped' => false,
                'class' => 'SD\UserBundle\Entity\User',
                'empty_value' => '',
                'data' => $user,
                'query_builder' => function (\SD\UserBundle\Entity\UserRepository $repository) {
                        return $repository->createQueryBuilder('u')
                            ->innerJoin('u.stuff', 's')
                            ->leftJoin('s.status', 'st')
                            ->where('st.lukey = :status')
                            ->orWhere('st.id is NULL')
                            ->orderBy('u.lastName', 'asc')
                            ->setParameter(':status', "worked");
                        //->setParameter(':fired', true, \PDO::PARAM_BOOL);
                }
            ));

            $builder->add('matcher', 'entity', array(
                'mapped' => false,
                'class' => 'SD\UserBundle\Entity\User',
                'multiple' => 'true',
                'empty_value' => '',
                'required' => false,
                //'data' => $user,
                'query_builder' => function (\SD\UserBundle\Entity\UserRepository $repository) {
                        return $repository->createQueryBuilder('u')
                            ->innerJoin('u.stuff', 's')
                            ->leftJoin('s.status', 'st')
                            ->where('st.lukey = :status')
                            ->orWhere('st.id is NULL')
                            ->orderBy('u.lastName', 'asc')
                            ->setParameter(':status', "worked");
                        //->setParameter(':fired', true, \PDO::PARAM_BOOL);
                }
            ));

            $builder->add('responsible', 'hidden', array(
                'mapped' => false
            ));

        }
/*        $builder->add('performer', 'entity', array(
            'class' => 'SD\UserBundle\Entity\User',
            'empty_value' => ''
        ));*/
        $builder
            ->add('title')
            ->add('description')

            ->add('startDate', 'datetime', array(
                'data' => new \DateTime(),
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy HH:mm'
            ))
            ->add('endDate', 'datetime', array(
                'mapped' => false,
                'widget' => 'single_text',
                'format' => 'dd.MM.yyyy HH:mm'
            ))
            ->add('files', 'file', array(
                'mapped' => false,
                'required' => false,
                'multiple' => true
            ));
        $patterns = $container->get("doctrine")->getRepository('SDTaskBundle:TaskPattern')
            ->findAll();
        if ($patterns && $stuff) {
            $builder->add('pattern', 'entity', array(
                'mapped' => false,
                'class' => 'SD\TaskBundle\Entity\TaskPattern',
                'empty_value' => '',
                'required' => false,
            ));
        }

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($container) {
                /** @var \SD\TaskBundle\Entity\Task $data */
                $data = $event->getData();

                $form = $event->getForm();
                $formData = $container->get('request')->get($form->getName());

                $translator = $container->get('translator');
                $endDate = new \DateTime($formData['endDate']);
                if ($data->getStartDate()->format('U') >= $endDate->format('U')) {
                    $msgString = "Start date can't be greater then stop date";

                    $msg = $translator->trans($msgString, array(), 'SDCalendarBundle');

                    $form->addError(new FormError($msg));
                }
            }
        );
    }

      /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SD\TaskBundle\Entity\Task',
            'translation_domain' => 'SDTaskBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'taskForm1';
    }
}
