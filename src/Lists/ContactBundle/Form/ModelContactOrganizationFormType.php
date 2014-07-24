<?php

namespace Lists\ContactBundle\Form;

use Lists\ContactBundle\Entity\ModelContact;
use Lists\ContactBundle\Entity\ModelContactLevelRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Lists\ContactBundle\Entity\ModelContactRepository;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Class ModelContactOrganizationFormType
 */
class ModelContactOrganizationFormType extends AbstractType
{
    protected $container;
    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;

         /** @var \SD\UserBundle\Entity\UserRepository $ur */
        $this->ur = $this->container->get('sd_user.repository');
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $container = $this->container;

        $builder
            ->add('modelName', 'hidden', array(
                'data' => ModelContactRepository::MODEL_ORGANIZATION
            ))
            ->add('modelId', 'hidden')
            ->add('firstName')
            ->add('lastName')
            ->add('middleName')
            ->add('phone1')
            ->add('phone2')
            ->add('position')
            ->add('birthday', 'birthday', array(
                'widget' => 'single_text',
                'format' => 'dd.M.yyyy'
            ))
            ->add('email')
            ->add('type', null, array(
                'required' => true
            ))
            ->add('level', null, array(
                'required' => true,
                'empty_value' => '',
                'query_builder' => function (ModelContactLevelRepository $repository) {
                        return $repository->createQueryBuilder('mcl')
                            ->orderBy('mcl.digit', 'ASC');
                }
            ));

        $builder
            ->add('add', 'submit')
            ->add('cancel', 'button');

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) use ($container) {
                /** @var ModelContact $data */
                /*$data = $event->getData();

                if ($data['birthday']) {
                    // Super hack)
                    if (sizeof($data['birthday']) < 5) {
                        $birthday = date('d.m.Y', $data['birthday']);
                        $data['birthday'] = $birthday;
                        $event->setData($data);
                    }
                }*/
            }
        );
        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($container) {
                $data = $event->getData();

                $form = $event->getForm();

                if ($data) {
                    if (!$data->getPhone1() && !$data->getPhone2() && !$data->getEmail()) {
                        $translator = $container->get('translator');

                        $msg = $translator->trans("Enter Phone or Phone mob or email", array(), 'ListsContactBundle');

                        $form->get('phone1')->addError(new FormError($msg));
                    }
                    if (!$data->getLevel()) {
                        $translator = $container->get('translator');

                        $msg = $translator->trans("Enter level", array(), 'ListsContactBundle');

                        $form->get('level')->addError(new FormError($msg));
                    }
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
            'data_class' => 'Lists\ContactBundle\Entity\ModelContact',
            'translation_domain' => 'ListsContactBundle'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'modelContactOrganizationForm';
    }
}
